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
     * Crea usuarios de prueba con los roles principales del sistema.
     */
    public function run(): void
    {
        // Se buscan los roles por nombre para no depender de IDs fijos entre entornos.
        $adminRoleId = DB::table('roles')->where('nombre_rol', 'admin')->value('id');
        $customerRoleId = DB::table('roles')->where('nombre_rol', 'customer')->value('id');

        // Usuarios base para probar el panel de administración y la parte de cliente.
        $admin = User::create([
            'name' => 'Admin Nexus',
            'email' => 'admin@nexusgear.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);
        
        // La fecha de asignación queda registrada en la tabla pivote.
        $admin->roles()->attach($adminRoleId, ['asignado_el' => now()]);

        $customer = User::create([
            'name' => 'Juan Cliente',
            'email' => 'juan@example.com',
            'password' => Hash::make('user123'),
            'email_verified_at' => now(),
        ]);
        
        $customer->roles()->attach($customerRoleId, ['asignado_el' => now()]);
    }
}
