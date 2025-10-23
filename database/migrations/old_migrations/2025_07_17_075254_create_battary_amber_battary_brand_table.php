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
        Schema::create('battary_amber_battary_brand', function (Blueprint $table) {
            $table->id();
            $table->foreignId('battary_amber_id')->constrained('battary_ambers')->onDelete('cascade');
            $table->foreignId('battary_brand_id')->constrained('battary_brands')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('battary_amber_battary_brand');
    }
};
