<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RouteTest extends TestCase
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
            // Asignar los permisos necesarios
            $this->admin->givePermissionTo('view_any_producto');
            $this->admin->givePermissionTo('create_producto');
            $this->admin->givePermissionTo('update_producto');
            $this->admin->givePermissionTo('view_any_comprador');
            $this->admin->givePermissionTo('view_any_pedido');
            $this->admin->givePermissionTo('view_any_permission');
            $this->admin->givePermissionTo('view_any_role');
            $this->admin->givePermissionTo('view_any_user');
            $this->admin->givePermissionTo('create_user');
        }
    }

    /**
     * Test if an admin can access the dashboard.
     */
    public function test_admin_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get('/gdo');
        $response->assertStatus(200);
    }

    /**
     * Test if an admin can access the comprador listing page.
     */
    public function test_admin_can_access_comprador_listing(): void
    {
        $response = $this->actingAs($this->admin)->get('/gdo/compradors');
        $response->assertStatus(200);
    }

    /**
     * Test if an admin can access the login page.
     */
    public function test_admin_can_access_login_page(): void
    {
        $response = $this->get('/gdo/login');
        $response->assertStatus(200);
    }

    /**
     * Test if an admin can logout.
     */
    public function test_admin_can_logout(): void
    {
        $this->withoutMiddleware();
        $response = $this->actingAs($this->admin)->post('/gdo/logout');
        $response->assertStatus(302); // Redirección después del logout
    }

    /**
     * Test if an admin can access the password reset request page.
     */
    public function test_admin_can_access_password_reset_request(): void
    {
        $response = $this->get('/gdo/password-reset/request');
        $response->assertStatus(200);
    }

    /**
     * Test if an admin can access the password reset page.
     */
    public function test_admin_can_access_password_reset(): void
    {
        $this->withoutMiddleware();
        $response = $this->get('/gdo/password-reset/reset');
        $response->assertStatus(200);
    }

    /**
     * Test if an admin can access the pedidos listing page.
     */
    public function test_admin_can_access_pedidos_listing(): void
    {
        $response = $this->actingAs($this->admin)->get('/gdo/pedidos');
        $response->assertStatus(200);
    }

    /**
     * Test if an admin can access the create pedidos page.
     */
    public function test_admin_can_access_create_pedidos_page(): void
    {
        $response = $this->actingAs($this->admin)->get('/gdo/pedidos/create');
        $response->assertStatus(200);
    }
    
    /**
     * Test if an admin can access the permissions listing page.
     */
    public function test_admin_can_access_permissions_listing(): void
    {
        $response = $this->actingAs($this->admin)->get('/gdo/permissions');
        $response->assertStatus(200);
    }

    /**
     * Test if an admin can access the productos listing page.
     */
    public function test_admin_can_access_productos_listing(): void
    {
        $response = $this->actingAs($this->admin)->get('/gdo/productos');
        $response->assertStatus(200);
    }

    /**
     * Test if an admin can access the roles listing page.
     */
    public function test_admin_can_access_roles_listing(): void
    {
        $response = $this->actingAs($this->admin)->get('/gdo/roles');
        $response->assertStatus(200);
    }

    /**
     * Test if an admin can access the users listing page.
     */
    public function test_admin_can_access_users_listing(): void
    {
        $response = $this->actingAs($this->admin)->get('/gdo/users');
        $response->assertStatus(200);
    }

    /**
     * Test if an admin can access the create users page.
     */
    public function test_admin_can_access_create_users_page(): void
    {
        $response = $this->actingAs($this->admin)->get('/gdo/users/create');
        $response->assertStatus(200);
    }
}
