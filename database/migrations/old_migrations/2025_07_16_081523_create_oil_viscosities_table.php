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
        Schema::create('oil_viscosities', function (Blueprint $table) {
            $table->id();
            $table->string('grade');
            $table->text('description')->nullable();
            $table->foreignId('oil_brand_id')->constrained('oil_brands')->onDelete('cascade');
            $table->string('price_range')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oil_viscosities');
    }
};
