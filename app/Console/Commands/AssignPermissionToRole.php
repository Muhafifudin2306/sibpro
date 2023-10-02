<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AssignPermissionToRole extends Command
{
    protected $signature = 'permission:assign {role} {permission}';
    protected $description = 'Assign a permission to a role';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $roleName = $this->argument('role');
        $permissionName = $this->argument('permission');

        $role = Role::where('name', $roleName)->first();
        $permission = Permission::where('name', $permissionName)->first();

        if (!$role || !$permission) {
            $this->error('Role or permission not found.');
            return;
        }

        $role->givePermissionTo($permission);

        $this->info('Permission ' . $permissionName . ' assigned to role ' . $roleName);
    }
}
