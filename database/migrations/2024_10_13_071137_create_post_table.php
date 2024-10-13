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
        Schema::create('posts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->string('slug',191)->nullable();
            $table->string('title',191)->nullable();
            $table->text('teaser',191)->nullable();
            $table->longText('description')->nullable();
            $table->string('post_type')->nullable(); //['category','course','page','artikel','portofolio','gallery','template','event','testimoni','promo','newsletter','calendar','program','tim']
            $table->string('featured_image')->nullable();
            $table->string('visibility')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post');
    }
};
