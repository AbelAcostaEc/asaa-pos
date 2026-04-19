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
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('code')->unique(); // Código del lote (e.g., lote de compra)
            $table->decimal('initial_stock', 10, 2); // Stock inicial en unidades base
            $table->decimal('current_stock', 10, 2); // Stock actual en unidades base
            $table->date('purchase_date')->nullable(); // Fecha de compra
            $table->decimal('cost_price', 10, 2)->nullable(); // Precio de costo por unidad base
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};