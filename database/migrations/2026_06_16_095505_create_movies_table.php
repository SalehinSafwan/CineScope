<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('movies', function (Blueprint $table) {
        $table->id('movie_id');
        $table->string('title', 255);
        $table->integer('release_year');
        $table->decimal('rating', 3, 1)->default(0.0);
        $table->string('language', 50)->default('English');
        $table->text('description')->nullable();
        $table->string('poster_url', 500)->nullable();

        $table->unsignedBigInteger('director_id')->nullable();
        $table->foreign('director_id')
              ->references('director_id')
              ->on('directors')
              ->onDelete('set null');

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
