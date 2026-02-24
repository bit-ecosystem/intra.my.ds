<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\Hrm\Staff;
use App\Models\User;
use App\Services\RoleSyncService;
use DutchCodingCompany\FilamentSocialite\Events\Login;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SyncKeycloakAttributes
{
    public function handle(Login $login): void
    {
        $user = $login->socialiteUser->user;   // \App\Models\User
        $attributes = $login->oauthUser->user;       // Raw Keycloak attributes

        $username = $attributes['preferred_username'] ?? null;
        $useremail = $attributes['email'] ?? null;
        // dd($username.'...'.$useremail);
        // Ensure app_authentication_secret exists
        if (empty($user->app_authentication_secret)) {
            $user->app_authentication_secret = Str::random(32);
            $user->save();
        }

        $normalizedun = Str::lower(trim($username));
        $normalizedue = Str::lower(trim($useremail));

        // Resolve Staff by attributes (login/company_email) OR staff_number
        $staff = Staff::query()
            ->where(function ($q) use ($normalizedun): void {
                $q->whereHas('personAttributes', function ($attr) use ($normalizedun): void {
                    $attr->where(function ($w) use ($normalizedun): void {
                        $w->where(function ($x) use ($normalizedun): void {
                            $x->where('key', 'login')
                                ->whereRaw('LOWER(value) = ?', [$normalizedun]);
                        })->orWhere(function ($y) use ($normalizedun): void {
                            $y->where('key', 'company_email')
                                ->whereRaw('LOWER(value) = ?', [$normalizedun]);
                        });
                    });
                })->orWhere(function ($q2) use ($normalizedun): void {
                    $q2->whereRaw('LOWER(CAST(staff_number AS CHAR)) = ?', [$normalizedun]);
                });

            })
            ->orwhere(function ($q) use ($normalizedue): void {
                $q->whereHas('personAttributes', function ($attr) use ($normalizedue): void {
                    $attr->where(function ($w) use ($normalizedue): void {
                        $w->where(function ($x) use ($normalizedue): void {
                            $x->where('key', 'login')
                                ->whereRaw('LOWER(value) = ?', [$normalizedue]);
                        })->orWhere(function ($y) use ($normalizedue): void {
                            $y->where('key', 'company_email')
                                ->whereRaw('LOWER(value) = ?', [$normalizedue]);
                        });
                    });
                })->orWhere(function ($q2) use ($normalizedue): void {
                    $q2->whereRaw('LOWER(CAST(staff_number AS CHAR)) = ?', [$normalizedue]);
                });

            })
            ->orderBy('id')
            ->first();

        // Use a transaction to keep association + role mirroring atomic
        DB::transaction(function () use ($user, $staff): void {
            // 1) Association policy
            if ($staff) {
                if (is_null($staff->user_id)) {
                    // ✅ Safe to link
                    $staff->user_id = $user->id;
                    $staff->save();
                } elseif ((int) $staff->user_id !== (int) $user->id) {
                    // ❌ Conflict: Staff is already linked to a different user
                    // → Prevent login & instruct admin workflow
                    $message = 'Your Staff record is already linked to a different user. '.
                        'Please contact an administrator to unlink (set staff user to null) before linking.';
                    // Flash a warning so the login screen can display it
                    Session::flash('auth_warning', $message);

                    Log::warning('Login prevented: Staff already linked to another user.', [
                        'staff_id' => $staff->id,
                        'staff_user_id' => $staff->user_id,
                        'login_user_id' => $user->id,
                    ]);

                    // Abort the request with a 403 (or 409) to stop the login pipeline
                    throw new HttpException(403, $message);
                }
            } else {
                // Optional: If no Staff found, you can choose to disallow login or let it pass.
                // If you want to allow login without staff, do nothing here.
                // If you want to block, uncomment below:
                // $msg = 'No matching Staff record found for your login. Please contact the administrator.';
                // Session::flash('auth_warning', $msg);
                // throw new HttpException(403, $msg);
            }

            // 2) Mirror roles when allowed
            if ($staff) {
                app(RoleSyncService::class)->syncFromStaff($user, $staff);
            } else {
                // Optional: strip staff-derived roles if you disallow login without staff
                // app(RoleSyncService::class)->syncFromStaff($user, null);
            }
        });
    }
}
