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

        $users = [
            [
                'name' => 'Admin Nexus',
                'email' => 'admin@nexusgear.com',
                'password' => 'admin123',
                'role_id' => $adminRoleId,
            ],
            [
                'name' => 'Juan Cliente',
                'email' => 'juan@example.com',
                'password' => 'user123',
                'role_id' => $customerRoleId,
            ],
            [
                'name' => 'María Ergonomía',
                'email' => 'maria@example.com',
                'password' => 'user123',
                'role_id' => $customerRoleId,
            ],
        ];

        foreach ($users as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                ],
            );

            $user->forceFill(['email_verified_at' => $user->email_verified_at ?? now()])->save();

            if ($data['role_id']) {
                // La fecha de asignación queda registrada en la tabla pivote.
                $user->roles()->syncWithoutDetaching([
                    $data['role_id'] => ['asignado_el' => now()],
                ]);
            }
        }
    }
}
