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
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('song_poster');
            $table->string('song');
            $table->foreignId('artist_id')->constrained()->onDelete('cascade');
            $table->foreignId('genre_id')->constrained()->onDelete('cascade');
            $table->foreignId('key_id')->constrained()->onDelete('cascade');
            $table->foreignId('license_id')->constrained()->onDelete('cascade');
            $table->foreignId('type_id')->constrained()->onDelete('cascade');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->integer('bpm')->default(0);
            $table->decimal('price', 10, 2)->default(0.00);
            $table->boolean('is_published')->default(true);
             $table->boolean('is_wishlisted')->default(false);
            $table->timestamps();

            $table->index('gender');
            $table->index('is_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('songs');
    }
};
