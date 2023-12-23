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
        Schema::create('external_incomes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pos_id')->nullable();
            $table->text('income_desc')->nullable();
            $table->date('income_period')->nullable();
            $table->decimal('income_price', 10, 2)->nullable();
            $table->foreign('pos_id')->references('id')->on('point_of_sales')->onUpdate('cascade')
                ->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_incomes');
    }
};
