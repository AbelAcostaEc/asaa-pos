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
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->foreignId('presentation_id')->nullable()->constrained('presentations')->onDelete('cascade')->after('product_id');
            $table->decimal('quantity_presentation', 10, 2)->nullable()->after('quantity'); // Cantidad vendida en esa presentación (e.g., 4 paquetes)
            $table->decimal('units_per_presentation', 10, 2)->nullable()->after('quantity_presentation'); // Unidades por presentación (snapshot, e.g., 25)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropForeign(['presentation_id']);
            $table->dropColumn(['presentation_id', 'quantity_presentation', 'units_per_presentation']);
        });
    }
};