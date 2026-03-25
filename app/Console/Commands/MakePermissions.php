<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Bites\Hrm\Models\Staff;
use App\Models\RoleMapper;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MakePermissions extends Command
{
    protected $signature = 'bites:make-permissions';

    protected $description = 'Generate CRUD permissions for all models under App\\Models';

    public function handle(): int
    {
        $this->info('Generating permissions...');

        // $panelIds = array_keys(Filament::getPanels());
        $models = $this->getAllModelClasses();

        $panels = config('bites.panels');

        foreach ($panels as $panelId => $panelConfig) {
            $role = $panelConfig['role_can_access'];   // ← this is what you want
            $permissionName = sprintf('go_%s_panel', $panelId);
            $permission = Permission::findOrCreate($permissionName, 'web');
            // $role=$role.[$panel];
            // dd($role);
            $this->line('Sync: to '.$permission);
            $permission->roles()->detach();
            $permission->syncRoles($role);
        }

        foreach ($models as $model) {
            $alias = $this->aliasFromFqcn($model);

            $this->createPermission('viewany_'.$alias);
            $this->createPermission('view_'.$alias);
            $this->createPermission('create_'.$alias);
            $this->createPermission('update_'.$alias);
            $this->createPermission('delete_'.$alias);
            $this->createPermission('restore_'.$alias);
            $this->createPermission('forcedelete_'.$alias);
        }

        self::makeDeveloper();
        $this->info('Done.');

        return self::SUCCESS;
    }

    protected function createPermission(string $name): void
    {
        Permission::firstOrCreate(['name' => $name]);
        // $this->line("Created: {$name}");
    }

    /**
     * Convert FQCN like "Bites\Kbm\Lms\Models\Certificate" → "Lms_Certificate".
     */
    protected function aliasFromFqcn(string $fqcn): string
    {
        $parts = explode('\\', $fqcn);
        $i = array_search('Models', $parts, true);

        if ($i === false || $i == count($parts) - 1) {
            return class_basename($fqcn);
        }

        return implode('_', array_slice($parts, $i + 1));
    }

    /**
     * Recursively find all PHP classes under app/Models.
     */
    protected function getAllModelClasses()
    {
        $base = app_path('Models');

        $rii = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($base, \FilesystemIterator::SKIP_DOTS)
        );

        $classes = collect();

        foreach ($rii as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $relative = trim(str_replace($base, '', $file->getPathname()), DIRECTORY_SEPARATOR);
            $withoutExt = Str::beforeLast($relative, '.php');

            $fqcn = 'App\\Models\\'.str_replace(DIRECTORY_SEPARATOR, '\\', $withoutExt);

            if (class_exists($fqcn)) {
                $classes->push($fqcn);
            }
        }

        return $classes->values();
    }

    protected static function makeDeveloper(): void
    {
        $role = RoleMapper::updateOrCreate(['role_name' => 'jt_developer'], [
            'scope' => 'global',
            'enabled' => true,
            'label' => 'Developer role for this app.',
            'category' => 'canonical',
        ]);

        $role = Role::where('name', 'jt_developer')->first();

        $permissions = Permission::all();
        $role->syncPermissions($permissions);

        Staff::where('staff_number', '153582')->first()->staffRoleLinks()->attach($role);
        Staff::where('staff_number', '156183')->first()->staffRoleLinks()->attach($role);
    }
}
