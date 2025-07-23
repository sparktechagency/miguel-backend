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
        Schema::create('artists', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->string('singer',255)->nullable();
            $table->string('singer_writer',255)->nullable();
            $table->string('location',255)->nullable();
            $table->text('description')->nullable();
            $table->string('profile',255)->default('default/user.png');
            $table->enum('gender',['male','female','other'])->default('other');
            $table->boolean('is_wishlisted')->default(false);
            $table->boolean('is_followed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artists');
    }
};
