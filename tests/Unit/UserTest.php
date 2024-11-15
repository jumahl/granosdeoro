<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Livewire\Livewire;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear un usuario admin para las pruebas
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password')
        ]);

        // Crear rol y permisos de prueba
        $role = Role::create(['name' => 'admin']);
        $permission = Permission::create(['name' => 'test permission']);
        $admin->assignRole('admin');
    }

    /** @test */
    public function puede_crear_un_usuario_con_rol_y_permisos()
    {
        $this->actingAs(User::first());

        $role = Role::first();
        $permission = Permission::first();

        Livewire::test(CreateUser::class)
            ->set('data.name', 'Test User')
            ->set('data.email', 'test@example.com')
            ->set('data.password', 'password123')
            ->set('data.password_confirmation', 'password123')
            ->set('data.roles', [$role->id])
            ->set('data.permissions', [$permission->id])
            ->call('create')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue($user->hasRole('admin'));
        $this->assertTrue($user->hasPermissionTo('test permission'));
    }

    /** @test */
    public function valida_confirmacion_de_password()
    {
        $this->actingAs(User::first());

        Livewire::test(CreateUser::class)
            ->set('data.name', 'Test User')
            ->set('data.email', 'test@example.com')
            ->set('data.password', 'password123')
            ->set('data.password_confirmation', 'diferente123')
            ->call('create')
            ->assertHasErrors(['data.password']);
    }

    /** @test */
    public function valida_longitud_minima_de_password()
    {
        $this->actingAs(User::first());

        Livewire::test(CreateUser::class)
            ->set('data.name', 'Test User')
            ->set('data.email', 'test@example.com')
            ->set('data.password', '1234')
            ->set('data.password_confirmation', '1234')
            ->call('create')
            ->assertHasErrors(['data.password']);
    }

    /** @test */
    public function puede_actualizar_usuario_sin_cambiar_password()
    {
        $this->actingAs(User::first());

        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'password' => Hash::make('oldpassword')
        ]);

        $oldPassword = $user->password;

        Livewire::test(EditUser::class, [
            'record' => $user->id,
        ])
        ->set('data.name', 'Updated Name')
        ->set('data.email', 'updated@example.com')
        ->call('save')
        ->assertHasNoErrors();

        $user->refresh();

        $this->assertEquals('Updated Name', $user->name);
        $this->assertEquals('updated@example.com', $user->email);
        $this->assertEquals($oldPassword, $user->password);
    }

    /** @test */
    public function puede_actualizar_roles_y_permisos()
    {
        $this->actingAs(User::first());

        $user = User::factory()->create();
        $newRole = Role::create(['name' => 'editor']);
        $newPermission = Permission::create(['name' => 'edit posts']);

        Livewire::test(EditUser::class, [
            'record' => $user->id,
        ])
        ->set('data.roles', [$newRole->id])
        ->set('data.permissions', [$newPermission->id])
        ->call('save')
        ->assertHasNoErrors();

        $this->assertTrue($user->fresh()->hasRole('editor'));
        $this->assertTrue($user->fresh()->hasPermissionTo('edit posts'));
    }
}