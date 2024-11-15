<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Comprador;
use App\Filament\Resources\CompradorResource\Pages\CreateComprador;
use App\Filament\Resources\CompradorResource\Pages\EditComprador;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Livewire\Livewire;

class CompradorTest extends TestCase
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
            $this->admin->givePermissionTo('create_comprador');
        }
    }

    /**
     * Test if an admin can access the buyer listing page.
     *
     * @return void
     */
    public function test_admin_can_access_buyer_listing(): void
    {
        // Asignar permisos necesarios al rol admin
        $this->admin->givePermissionTo('view_any_comprador');

        // Actuar como el usuario adminñ
        $response = $this->actingAs($this->admin)->get('/gdo/compradors');

        // Verificar que el acceso sea permitido (status 200)
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_comprador()
    {
        // Arrange
        $newCompradorData = [
            'nombre' => 'Comprador de Prueba',
            'direccion' => 'Calle 123',
            'contacto' => '123456789',
        ];

        // Pre-assertion
        $this->assertDatabaseCount('compradors', 0);

        // Act
        Livewire::actingAs($this->admin) // Actuar como el usuario admin
            ->test(CreateComprador::class)
            ->set('nombre', $newCompradorData['nombre'])
            ->set('direccion', $newCompradorData['direccion'])
            ->set('contacto', $newCompradorData['contacto'])
            ->call('create') // Llamar al método de creación
            ->assertHasNoErrors(); // Verificar que no haya errores en el formulario

        // Assert
        $this->assertDatabaseCount('compradors', 1);
        $this->assertDatabaseHas('compradors', [
            'nombre' => $newCompradorData['nombre'],
            'direccion' => $newCompradorData['direccion'],
            'contacto' => $newCompradorData['contacto'],
        ]);
    }

    /** @test */
    public function it_can_edit_comprador()
    {
        // Arrange
        $comprador = Comprador::factory()->create([
            'nombre' => 'Comprador Original',
            'direccion' => 'Calle 456',
            'contacto' => '987654321',
        ]);
        $updatedCompradorData = [
            'nombre' => 'Comprador Actualizado',
            'direccion' => 'Calle 789',
            'contacto' => '123123123',
        ];

        // Act
        Livewire::actingAs($this->admin) // Actuar como el usuario admin
            ->test(EditComprador::class, ['record' => $comprador->getKey()])
            ->set('nombre', $updatedCompradorData['nombre'])
            ->set('direccion', $updatedCompradorData['direccion'])
            ->set('contacto', $updatedCompradorData['contacto'])
            ->call('save') // Llamar al método de edición
            ->assertHasNoErrors(); // Verificar que no haya errores en el formulario

        // Assert
        $this->assertDatabaseHas('compradors', [
            'id' => $comprador->id,
            'nombre' => $updatedCompradorData['nombre'],
            'direccion' => $updatedCompradorData['direccion'],
            'contacto' => $updatedCompradorData['contacto'],
        ]);
    }
}