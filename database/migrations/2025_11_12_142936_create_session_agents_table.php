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
        Schema::create('session_agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('session_chats', 'id')->cascadeOnDelete();
            $table->foreignId('agent_id')->constrained('agents', 'id')->cascadeOnDelete();
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_agents');
    }
};
