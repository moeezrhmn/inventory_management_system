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
        Schema::create('labour_work', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('labour_id'); 
            $table->unsignedBigInteger('p_id'); 
            $table->integer('pieces')->default(0); 
            $table->float('payment', 8, 2)->default(0); 
            $table->text('description')->nullable(); 
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
        Schema::dropIfExists('labour_work');
    }
};
