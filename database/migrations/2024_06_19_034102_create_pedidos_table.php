<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('id_comprador');
        $table->date('fecha_pedido');
        $table->integer('cantidad');
        $table->decimal('total', 10, 2);
        $table->enum('status', ['en proceso', 'entregado', 'cancelado'])->default('en proceso');
        $table->timestamps();

        $table->foreign('id_comprador')->references('id')->on('compradors')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
