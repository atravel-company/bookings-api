<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Spatie\Permission\Models\Role;
use Config;

class AssignAdminRole extends Command
{
    protected $signature = 'admin:assign';
    protected $description = 'Assign admin role to users defined in config';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $adminRole = Role::where('name', 'admin')->first();
        if (!$adminRole) {
            $this->error('Admin role not found. Please run the seeder first.');
            return;
        }

        $adminUsers = Config::get('admin.admin_users');

        foreach ($adminUsers as $email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                if (!$user->hasRole('admin')) {
                    $user->assignRole('admin');
                    $this->info("Assigned admin role to user: $email");
                } else {
                    $this->info("User $email already has admin role.");
                }
            } else {
                $this->error("User $email not found.");
            }
        }

        $this->info('Admin role assignment completed.');
    }
}
