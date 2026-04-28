<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Obtenemos los IDs de los roles que acabamos de crear
        $adminRoleId = DB::table('roles')->where('nombre_rol', 'admin')->value('id');
        $customerRoleId = DB::table('roles')->where('nombre_rol', 'customer')->value('id');

        // 2. Crear un Administrador
        $admin = User::create([
            'name' => 'Admin Nexus',
            'email' => 'admin@nexusgear.com',
            'password' => Hash::make('admin123'), // Siempre encriptada
            'email_verified_at' => now(),
        ]);
        
        // Adjuntar rol en la tabla pivote con el campo 'asignado_el'
        $admin->roles()->attach($adminRoleId, ['asignado_el' => now()]);

        // 3. Crear un Usuario Cliente
        $customer = User::create([
            'name' => 'Juan Cliente',
            'email' => 'juan@example.com',
            'password' => Hash::make('user123'),
            'email_verified_at' => now(),
        ]);
        
        $customer->roles()->attach($customerRoleId, ['asignado_el' => now()]);
    }
}
