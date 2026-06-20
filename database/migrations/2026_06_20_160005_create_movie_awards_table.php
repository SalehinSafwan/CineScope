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
    Schema::create('movie_awards', function (Blueprint $table) {
        $table->unsignedBigInteger('movie_id');
        $table->unsignedBigInteger('award_id');

        $table->foreign('movie_id')
              ->references('movie_id')
              ->on('movies')
              ->onDelete('cascade');

        $table->foreign('award_id')
              ->references('award_id')
              ->on('awards')
              ->onDelete('cascade');

        $table->primary(['movie_id', 'award_id']);
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movie_awards');
    }
};
