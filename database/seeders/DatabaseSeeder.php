<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Producto;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear permisos
        $permissions = [
            'view_any_pedidos',
            'view_pedidos',
            'create_pedidos',
            'update_pedidos',
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

        // Crear productos
        $productos = [
            ['nombre' => 'Producto 1', 'descripcion' => 'Descripción del Producto 1', 'precio' => 100, 'cantidad_en_existencia' => 50],
            ['nombre' => 'Producto 2', 'descripcion' => 'Descripción del Producto 2', 'precio' => 200, 'cantidad_en_existencia' => 30],
            ['nombre' => 'Producto 3', 'descripcion' => 'Descripción del Producto 3', 'precio' => 150, 'cantidad_en_existencia' => 20],
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }
    }
}