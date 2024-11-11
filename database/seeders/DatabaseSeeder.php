<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear permisos
        $permissions = [
            'view_any_producto',
            'view_producto',
            'create_producto',
            'update_producto',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Crear usuario admin
        $user = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
        ]);

        // Crear rol admin y asignarle permisos
        $role = Role::firstOrCreate(['name' => 'admin']);
        $role->syncPermissions($permissions); // Asignar permisos al rol
        $user->assignRole('admin'); // Asignar rol al usuario
    }
}