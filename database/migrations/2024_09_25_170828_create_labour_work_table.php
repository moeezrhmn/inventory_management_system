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
        Schema::create('labourWork', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('labour_id'); // Labour reference
            $table->unsignedBigInteger('p_id'); // Item reference
            $table->integer('pieces')->default(0); // Number of pieces, default is 1
            $table->float('payment', 8, 2)->default(0); // Payment field, default 0, float with 2 decimal places
            $table->text('description')->nullable(); // Optional description field
            // Define foreign keys (optional, but good practice if you have related tables)
            $table->foreign('labour_id')->references('id')->on('labours')->onDelete('cascade');
            $table->foreign('p_id')->references('id')->on('products')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labourWork');
    }
};
