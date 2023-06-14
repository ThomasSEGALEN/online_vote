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
        Schema::create('label_set_vote', function (Blueprint $table) {
            $table->foreignId('label_set_id')->constrained('label_sets')->onDelete('cascade');
            $table->foreignId('vote_id')->constrained('votes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('label_set_vote');
    }
};
