<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Pedido;
use App\Models\Comprador;
use App\Filament\Resources\PedidoResource\Pages\CreatePedido;
use App\Filament\Resources\PedidoResource\Pages\EditPedido;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Livewire\Livewire;

class PedidoTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Ejecutar el seeder para configurar roles y permisos
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        // Crear o obtener el usuario admin
        $this->admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            ['name' => 'admin', 'password' => bcrypt('password')]
        );

        // Asignar el rol admin al usuario
        $this->admin->assignRole('admin');

        // Asignar permisos necesarios al rol admin
        $this->admin->givePermissionTo(['create_pedido', 'view_any_pedido']);
    }

    /**
     * Test if an admin can access the order listing page.
     *
     * @return void
     */
    public function test_admin_can_access_order_listing(): void
    {
        // Actuar como el usuario admin
        $response = $this->actingAs($this->admin)->get('/gdo/pedidos');

        // Verificar que el acceso sea permitido (status 200)
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_pedido()
    {
        // Arrange
        $comprador = Comprador::factory()->create();
        $newPedidoData = [
            'id_comprador' => $comprador->id,
            'fecha_pedido' => now()->toDateString(),
            'total' => 100.00,
            'status' => 'pendiente',
        ];

        // Pre-assertion
        $this->assertDatabaseCount('pedidos', 0);

        // Act
        Livewire::actingAs($this->admin) // Actuar como el usuario admin
            ->test(CreatePedido::class)
            ->set('id_comprador', $newPedidoData['id_comprador'])
            ->set('fecha_pedido', $newPedidoData['fecha_pedido'])
            ->set('total', $newPedidoData['total'])
            ->set('status', $newPedidoData['status'])
            ->call('create') // Llamar al método de creación
            ->assertHasNoErrors(); // Verificar que no haya errores en el formulario

        // Assert
        $this->assertDatabaseCount('pedidos', 1);
        $this->assertDatabaseHas('pedidos', [
            'id_comprador' => $newPedidoData['id_comprador'],
            'fecha_pedido' => $newPedidoData['fecha_pedido'],
            'total' => $newPedidoData['total'],
            'status' => $newPedidoData['status'],
        ]);
    }

    /** @test */
    public function it_can_edit_pedido()
    {
        // Arrange
        $pedido = Pedido::factory()->create([
            'id_comprador' => Comprador::factory()->create()->id,
            'fecha_pedido' => now()->toDateString(),
            'total' => 100.00,
            'status' => 'pendiente',
        ]);
        $updatedPedidoData = [
            'fecha_pedido' => now()->addDay()->toDateString(),
            'total' => 150.00,
            'status' => 'completado',
        ];

        // Act
        Livewire::actingAs($this->admin) // Actuar como el usuario admin
            ->test(EditPedido::class, ['record' => $pedido->getKey()])
            ->set('id_comprador', $pedido->id_comprador)
            ->set('fecha_pedido', $updatedPedidoData['fecha_pedido'])
            ->set('total', $updatedPedidoData['total'])
            ->set('status', $updatedPedidoData['status'])
            ->call('save') // Llamar al método de edición
            ->assertHasNoErrors(); // Verificar que no haya errores en el formulario

        // Assert
        $this->assertDatabaseHas('pedidos', [
            'id' => $pedido->id,
            'id_comprador' => $pedido->id_comprador,
            'fecha_pedido' => $updatedPedidoData['fecha_pedido'],
            'total' => $updatedPedidoData['total'],
            'status' => $updatedPedidoData['status'],
        ]);
    }
}