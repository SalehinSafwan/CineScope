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
    Schema::create('movie_production', function (Blueprint $table) {
        $table->unsignedBigInteger('movie_id');
        $table->unsignedBigInteger('company_id');

        $table->foreign('movie_id')
              ->references('movie_id')
              ->on('movies')
              ->onDelete('cascade');

        $table->foreign('company_id')
              ->references('company_id')
              ->on('production_companies')
              ->onDelete('cascade');

        $table->primary(['movie_id', 'company_id']);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movie_production');
    }
};
