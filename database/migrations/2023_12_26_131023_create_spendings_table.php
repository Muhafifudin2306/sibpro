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
        Schema::create('spendings', function (Blueprint $table) {
            $table->id();
            $table->date('spending_date');
            $table->string('spending_desc')->nullable();
            $table->boolean('spending_type'); 
            $table->decimal('spending_price', 10, 2); 
            $table->unsignedBigInteger('atrribute_id')->nullable();
            $table->foreign('attribute_id')->references('id')->on('attributes')->onUpdate('cascade')
                ->onDelete('restrict');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spendings');
    }
};
