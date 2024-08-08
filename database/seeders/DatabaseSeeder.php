<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roleAsdministrador = Role::firstOrCreate(['name' => 'Administrador']); 
        $user = User::firstOrCreate(
            ['email' => env('USER_EMAIL')],
            ['name' => env('USER_NAME'), 'password' => Hash::make(env('USER_PASSWORD'))]
        );
        $user->syncRoles($roleAsdministrador);
        
    }
}
