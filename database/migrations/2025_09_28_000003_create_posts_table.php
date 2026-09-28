<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('game_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20); // clip | trophy | screenshot
            $table->string('caption', 200)->nullable();
            $table->string('media_url')->nullable();
            $table->string('trophy_name', 120)->nullable();
            $table->string('trophy_rarity', 30)->nullable();
            $table->decimal('trophy_rarity_percent', 5, 2)->nullable();
            $table->unsignedTinyInteger('pinned_position')->nullable(); // 1-3
            $table->timestamps();
        });

        Schema::create('post_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('emoji', 10); // fire | laugh | wow | clap | heart
            $table->unique(['post_id', 'user_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_reactions');
        Schema::dropIfExists('posts');
    }
};
