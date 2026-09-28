<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->unsignedSmallInteger('release_year')->nullable();
            $table->string('cover_url')->nullable();
            $table->string('placeholder_color_1', 9)->nullable();
            $table->string('placeholder_color_2', 9)->nullable();
            $table->string('external_id')->nullable()->index(); // IGDB ID
            $table->timestamps();
        });

        Schema::create('game_user', function (Blueprint $table) {
            $table->foreignId('game_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('hours')->default(0);
            $table->boolean('is_favorite')->default(false);
            $table->unsignedTinyInteger('favorite_position')->nullable();
            $table->primary(['game_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_user');
        Schema::dropIfExists('games');
    }
};
