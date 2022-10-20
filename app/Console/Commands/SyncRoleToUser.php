<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use App\Models\User;

class SyncRoleToUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:assignrole {role} {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign single role to user based on email';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $role = $this->argument('role');
        $user = $this->argument('user');

        $userExist = User::where('email',$user)->first();
        $roleExist = Role::where('name', $role)->first();

        if($userExist && $roleExist){
            if(!in_array($role, $userExist->getRoleNames()->toArray())){
                $userExist->assignRole($role);
                $this->info('Assign '.$role.' to user '.$user.' success');
            }
            $this->info('Role '.$role.' already assigned to user '.$user);
        }else{
            $this->info('Role and/or User not exist. Please contact admin');
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
