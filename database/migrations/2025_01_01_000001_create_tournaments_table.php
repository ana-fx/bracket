<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('cover_image')->nullable();
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('location')->nullable();
            $table->text('location_map')->nullable();
            $table->text('terms_and_conditions')->nullable();
            $table->enum('status', ['draft', 'active', 'completed'])->default('draft');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Creator
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tournaments');
    }
};
