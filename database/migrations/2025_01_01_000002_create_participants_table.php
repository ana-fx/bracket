<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tournament_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('affiliation')->nullable(); // e.g. "Teknik Informatika"
            $table->integer('seed')->nullable(); // 1, 2, 3...
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
