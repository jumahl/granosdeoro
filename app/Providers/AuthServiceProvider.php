<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Comprador;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use App\Policies\CompradorPolicy;
use App\Policies\PedidoPolicy;
use App\Policies\ProductoPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        
        
        //'App\Models\Model' => 'App\Policies\ModelPolicy',
        User::class => UserPolicy::class,
        Comprador::class => CompradorPolicy::class,
        Producto::class => ProductoPolicy::class,
        Pedido::class => PedidoPolicy::class,
        
        
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
