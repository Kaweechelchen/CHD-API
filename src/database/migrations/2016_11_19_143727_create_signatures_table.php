<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSignaturesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('signatures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('petition_id')->unsigned();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('city');
            $table->integer('postcode');
            $table->integer('page_number');
            $table->integer('index_on_page');
            $table->timestamps();

            $table->foreign('petition_id')
                ->references('id')
                ->on('petitions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('signatures');
    }
}
