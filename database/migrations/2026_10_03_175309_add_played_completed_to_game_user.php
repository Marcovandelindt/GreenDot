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
        Schema::table('game_user', function (Blueprint $table) {
            $table->boolean('is_played')->default(false)->after('is_playing');
            $table->boolean('is_completed')->default(false)->after('is_played');
        });
    }

    public function down(): void
    {
        Schema::table('game_user', function (Blueprint $table) {
            $table->dropColumn(['is_played', 'is_completed']);
        });
    }
};
