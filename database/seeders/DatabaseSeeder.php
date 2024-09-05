<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Configuracion;
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
        $email="david.criollo14@gmail.com";
        $user = User::firstOrCreate(
            ['email' => $email],
            ['name' => $email, 'password' => Hash::make($email)]
        );
        $user->syncRoles($roleAsdministrador);
        

        // crear configuracion
        $configuracion = Configuracion::firstOrCreate(
            ['id' => 1],
            [
                'frecuencia' => 'everyTenMinutes',
                'url_web_gps'=>'http://www.ecuatracker.com/',
                'token'=>'*****'
            ]
        );

        
    }
}
