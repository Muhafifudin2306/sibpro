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
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id'); 
            $table->unsignedBigInteger('year_id');
            $table->unsignedBigInteger('attribute_id');
            $table->decimal('debt_amount', 10, 2); 
            $table->date('due_date'); 
            $table->text('description')->nullable(); 
            $table->boolean('is_paid')->default(false); 
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('attribute_id')->references('id')->on('attributes')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('year_id')->references('id')->on('years')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
