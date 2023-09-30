<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User; // Sesuaikan dengan model pengguna Anda
use Spatie\Permission\Models\Role; // Sesuaikan dengan model Role Spatie

class AssignRole extends Command
{
    protected $signature = 'assign:role {user_id} {role_name}';
    protected $description = 'Assign a role to a user';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $userId = $this->argument('user_id');
        $roleName = $this->argument('role_name');

        $user = User::find($userId);
        $role = Role::where('name', $roleName)->first();

        if (!$user || !$role) {
            $this->error('User or role not found.');
            return;
        }

        $user->assignRole($role);

        $this->info('Role ' . $roleName . ' assigned to user ' . $user->name);
    }
}
