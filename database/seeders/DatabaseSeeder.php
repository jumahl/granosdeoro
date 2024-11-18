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
        // Crear permisos para Comprador
        $compradorPermissions = [
            'create_comprador',
            'update_comprador',
        ];

        // Crear permisos para Producto
        $productoPermissions = [
            'view_any_producto',
            'view_producto',
            'create_producto',
            'update_producto',
        ];

        // Crear permisos para Pedido
        $pedidoPermissions = [
            'create_pedido',
            'update_pedido',
            'view_any_pedido',
            
        ];

        // Combinar todos los permisos
        $permissions = array_merge($compradorPermissions, $productoPermissions, $pedidoPermissions);

        // Crear los permisos en la base de datos
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Crear usuario admin si no existe
        $user = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            ['name' => 'admin', 'password' => bcrypt('password')] // Asegúrate de definir una contraseña
        );

        // Crear rol admin y asignarle permisos
        $role = Role::firstOrCreate(['name' => 'admin']);
        $role->syncPermissions($permissions); // Asignar permisos al rol
        $user->assignRole('admin'); // Asignar rol al usuario
    }
}