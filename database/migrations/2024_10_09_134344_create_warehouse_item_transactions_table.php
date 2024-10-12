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
        Schema::create('warehouse_item_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_item_id')->references('id')->on('warehouse_items')->onDelete('cascade');
            $table->integer('quantity');
            $table->text('reference')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_item_transactions');
    }
};
