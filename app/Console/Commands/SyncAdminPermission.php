<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SyncAdminPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:syncadmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync superadmin to all permission';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $role = Role::where('name','Superadmin')->first();
        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);

        $this->info('Sync all permissions to superadmin');

        return Command::SUCCESS;
    }
}
