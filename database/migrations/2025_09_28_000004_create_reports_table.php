<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('reported_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('reason', 255);
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('added_players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('added_user_id')->constrained('users')->cascadeOnDelete();
            $table->boolean('accepted')->nullable(); // null=pending, true=yes, false=no
            $table->unique(['user_id', 'added_user_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('added_players');
        Schema::dropIfExists('reports');
    }
};
