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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['purchase', 'sale']); // compra o venta
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('cascade'); // Solo para compras
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('cascade'); // Para ventas, null si consumidor final
            $table->date('date');
            $table->decimal('total', 10, 2);
            $table->decimal('taxes', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2);
            $table->text('observations')->nullable();
            $table->string('invoice_number')->nullable(); // Número de factura básico
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};