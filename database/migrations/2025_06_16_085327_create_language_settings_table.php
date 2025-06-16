<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('language_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('language', ['id', 'en'])->default('id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('language_settings');
    }
};