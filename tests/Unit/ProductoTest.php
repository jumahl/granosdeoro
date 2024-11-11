<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Producto;
use App\Filament\Resources\ProductoResource\Pages\CreateProducto;
use App\Filament\Resources\ProductoResource\Pages\EditProducto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Livewire\Livewire;

class ProductoTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Ejecutar el seeder para configurar roles y permisos
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        // Verificar si el usuario admin ya existe
        $this->admin = User::where('email', 'admin@admin.com')->first();

        // Si no existe, crearlo
        if (!$this->admin) {

            $this->admin = User::factory()->create([
                'name' => 'admin',
                'email' => 'admin@admin.com',
            ]);

            // Asignar el rol admin al usuario
            $this->admin->assignRole('admin');
            // Asignar el permiso de creación
            $this->admin->givePermissionTo('create_producto');
        }
    }

    /**
     * Test if an admin can access the product listing page.
     *
     * @return void
     */
    public function test_admin_can_access_product_listing(): void
    {
        // Asignar permisos necesarios al rol admin
        $this->admin->givePermissionTo('view_any_producto');

        //dd($this->admin->getAllPermissions());
    
        // Actuar como el usuario admin
        $response = $this->actingAs($this->admin)->get('/gdo/productos');
    
        // Verificar que el acceso sea permitido (status 200)
        $response->assertStatus(200);
    }

    
    /** @test */
    public function it_can_create_producto()
    {
        // Arrange
        $newProductoData = [
            'nombre' => 'Producto de Prueba',
            'descripcion' => 'Descripción de prueba para el producto',
            'precio' => 50.00,
            'cantidad_en_existencia' => 20,
        ];
    
        // Pre-assertion
        $this->assertDatabaseCount('productos', 0);
    
        // Act
        Livewire::actingAs($this->admin) // Actuar como el usuario admin
            ->test(CreateProducto::class)
            ->set('nombre', $newProductoData['nombre'])
            ->set('descripcion', $newProductoData['descripcion'])
            ->set('precio', $newProductoData['precio'])
            ->set('cantidad_en_existencia', $newProductoData['cantidad_en_existencia'])
            ->call('create') // Llamar al método de creación
            ->assertHasNoErrors(); // Verificar que no haya errores en el formulario
    
        // Assert
        $this->assertDatabaseCount('productos', 1);
        $this->assertDatabaseHas('productos', [
            'nombre' => $newProductoData['nombre'],
            'descripcion' => $newProductoData['descripcion'],
            'precio' => $newProductoData['precio'],
            'cantidad_en_existencia' => $newProductoData['cantidad_en_existencia'],
        ]);
    }

    /** @test */ 
    public function it_can_edit_producto() { 
        // Arrange 
        $producto = Producto::factory()->create([ 
            'nombre' => 'Producto Original', 
            'descripcion' => 'Descripción original', 
            'precio' => 100.00, 
            'cantidad_en_existencia' => 10, 
        ]); 
        $updatedProductoData = [ 
            'precio' => 150.00, 
            'cantidad_en_existencia' => 15, 
        ]; 
        // Act 
        Livewire::actingAs($this->admin) // Actuar como el usuario admin 
            ->test(EditProducto::class, ['record' => $producto->getKey()]) 
            ->set('precio', $updatedProductoData['precio']) 
            ->set('cantidad_en_existencia', $updatedProductoData['cantidad_en_existencia']) 
            ->call('save') // Llamar al método de edición 
            ->assertHasNoErrors(); // Verificar que no haya errores en el formulario 
        // Assert 
        $this->assertDatabaseHas('productos', [ 
            'id' => $producto->id, 
            'precio' => $updatedProductoData['precio'], 
            'cantidad_en_existencia' => $updatedProductoData['cantidad_en_existencia'], 
        ]); 
    }
}