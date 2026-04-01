<?php

declare(strict_types=1);

namespace App\Models;

use Bites\Core\Organization\Models\JobPosition;
use Bites\Core\Organization\Models\OrgUnit;
use Bites\Hrm\Models\Staff;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthentication;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laravel\Passport\Contracts\OAuthenticatable;
use Laravel\Passport\HasApiTokens;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasAppAuthentication, HasAvatar, LdapAuthenticatable, OAuthenticatable
{
    use AuthenticatesWithLdap;
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use Notifiable;

    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'app_authentication_secret',
        'remember_token',

    ];

    protected $appends = [
        'people_attributes',
        'job_attributes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'app_authentication_secret' => 'encrypted',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function getFilamentAvatarUrl(): ?string
    {
        $number = $this->staff?->staff_old_number;

        // Always have a safe default
        $default = asset('images/unknown_user.png');

        if (! $number) {
            return $default;
        }

        $url = sprintf('http://10.40.3.41:8080/%s.jpg', $number);

        try {
            // Lightweight check without downloading the file body
            $response = Http::timeout(1.5)->head($url);

            if ($response->ok()) {
                return $url;
            }
        } catch (\Throwable $throwable) {
            // Swallow network/timeout errors and fall back
        }

        return $default;
    }

    public function getAppAuthenticationSecret(): ?string
    {
        // This method should return the user's saved app authentication secret.

        return $this->app_authentication_secret;
    }

    public function saveAppAuthenticationSecret(?string $secret): void
    {
        // This method should save the user's app authentication secret.

        $this->app_authentication_secret = $secret;
        $this->save();
    }

    public function getAppAuthenticationHolderName(): string
    {
        // In a user's authentication app, each account can be represented by a "holder name".
        // If the user has multiple accounts in your app, it might be a good idea to use
        // their email address as then they are still uniquely identifiable.

        return $this->email;
    }

    /**
     * @property Collection $personAttributes
     *
     * @method \Illuminate\Database\Eloquent\Relations\MorphMany personAttributes()
     */
    public function personAttributes()
    {
        return $this->morphMany(PersonAttribute::class, 'attributable');
    }

    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    public function jobPosition()
    {
        return $this->hasOneThrough(
            JobPosition::class, // final model
            Staff::class,       // intermediate model
            'user_id',          // FK on staff table referencing users.id
            'id',               // PK on job_positions (since job_positions has no FK to staff)
            'id',               // local key on users
            'job_position_id'   // local key on staff that matches job_positions.id
        );
    }

    /*
     * Check if user exists in socialite_users table and return provider or null.
     *
     * @return string|null
     */
    public function social_login(): ?string
    {
        $record = DB::table('socialite_users')
            ->where('user_id', $this->id)
            ->first();

        return $record?->provider ?? config('app.name');
    }

    protected function peopleAttributes(): Attribute
    {
        return Attribute::make(get: function () {
            // Get collections of PersonAttribute models (already loaded or lazy-loaded)
            $userAttrs = $this->relationLoaded('personAttributes')
                ? $this->personAttributes
                : $this->personAttributes()->get();
            $staffAttrs = $this->staff
                ? ($this->staff->relationLoaded('personAttributes')
                    ? $this->staff->personAttributes
                    : $this->staff->personAttributes()->get())
                : collect();
            // Map to key=>value
            $staffMap = $staffAttrs->mapWithKeys(fn ($attr): array => [$attr->key => $attr->value]);
            $userMap = $userAttrs->mapWithKeys(fn ($attr): array => [$attr->key => $attr->value]);
            // Base merged map: user overrides staff
            $merged = $staffMap->merge($userMap);
            // Append derived attributes (null-safe)
            if ($this->staff?->staff_number) {
                $merged->put('staff_number', $this->staff->staff_number);
            }

            // Merge with user taking precedence
            return $merged->all();
        });
    }

    protected function jobAttributes(): Attribute
    {
        return Attribute::make(get: function (): array {
            // Resolve org unit display (adjust the field name if yours differs)
            $orgUnitName = $this->staff?->orgUnit?->name ?? null;
            $orgUnitCode = $this->staff?->orgUnit?->code ?? null;
            // Resolve current job title
            $jobTitle = $this->staff?->jobPosition?->title;
            // Resolve superior's job position (title) and find a Staff on that position to read name
            $superiorPosition = $this->staff?->jobPosition?->superior;
            $superiorTitle = $superiorPosition?->title;
            // Fetch one Staff who holds the superior job position, then read its name accessor.
            // (Nickname comes from morphMany PersonAttribute on Staff.)
            $superiorNickname = null;
            if ($superiorPosition) {
                $superiorStaff = Staff::where('job_position_id', $superiorPosition->id)->first();
                $superiorNickname = $superiorStaff?->name; // accessor defined on Staff
            }

            // Format "Title (Nickname)" if name exists; otherwise just Title.
            $reportsTo = match (true) {
                $superiorTitle && $superiorNickname => sprintf('%s [%s]', $superiorNickname, $superiorTitle),
                $superiorTitle => $superiorTitle,
                default => null,
            };

            return [
                'job_title' => $jobTitle,
                'reports_to' => $reportsTo,
                'org_unit' => $orgUnitName,
                'org_unit_code' => $orgUnitCode,
            ];
        });
    }

    protected static function booted(): void
    {
        // Apply the team context to Spatie Permission
        $currentTeamId = Auth::user()?->staff?->org_unit_id;
        // dd($currentTeamId);
        if ($currentTeamId) {
            app(PermissionRegistrar::class)->setPermissionsTeamId($currentTeamId);
        }
    }

    public function getLdapGuidColumn(): string
    {
        return 'ldap_guid';
    }

    public function getLdapDomainColumn(): string
    {
        return 'ldap_domain';
    }
}
