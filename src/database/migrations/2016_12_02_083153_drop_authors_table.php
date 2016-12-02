<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::dropIfExists('authors');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::create('authors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('petition_id')->unsigned();
            $table->timestamps();

            $table->foreign('petition_id')
                ->references('id')
                ->on('petitions')
                ->onDelete('cascade');
        });
    }
}
