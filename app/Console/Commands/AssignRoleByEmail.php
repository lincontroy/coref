<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User; // adjust if you use another namespace
use Spatie\Permission\Models\Role;

class AssignRoleByEmail extends Command
{
    protected $signature = 'role:assign {email} {role}';
    protected $description = 'Assign a role to a user by email';

    public function handle()
    {
        $email = $this->argument('email');
        $roleName = $this->argument('role');

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("User with email {$email} not found.");
            return Command::FAILURE;
        }

        if (! Role::where('name', $roleName)->exists()) {
            $this->error("Role {$roleName} does not exist.");
            return Command::FAILURE;
        }

        $user->assignRole($roleName);
        $this->info("Role '{$roleName}' assigned to {$email} successfully.");
        return Command::SUCCESS;
    }
}
