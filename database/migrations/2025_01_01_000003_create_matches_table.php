<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tournament_id')->constrained()->cascadeOnDelete();

            $table->integer('round'); // 1 = Final, 2 = Semis, etc. (Or 1 = Round 1). Let's use 1 = Round of X, counting up.
            $table->integer('match_number'); // Sequential ID for visualization

            // The Binary Tree Links
            $table->foreignUuid('next_match_id')->nullable()->constrained('matches')->nullOnDelete();

            // Participants (Nullable for TBD)
            $table->foreignUuid('participant_1_id')->nullable()->constrained('participants')->nullOnDelete();
            $table->foreignUuid('participant_2_id')->nullable()->constrained('participants')->nullOnDelete();

            $table->foreignUuid('winner_id')->nullable()->constrained('participants')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
