<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createAdminUser();
    }

    public function createAdminUser()
    {
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@resumed.com',
            'password' => Hash::make('Sup3r@dm!n'),
            'created_at' => Carbon::now(),
        ]);    

        $role = Role::create(['name' => 'Superadmin']);   

        $permissions = Permission::pluck('id','id')->all();   

        $role->syncPermissions($permissions);    

        $user->assignRole([$role->id]);
    }
}
