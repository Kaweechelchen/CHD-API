<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePetitionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('petitions', function (Blueprint $table) {
            $table->integer('id')->unsigned()->unique()->index();
            $table->integer('number');
            $table->string('name')->nullable()->default(null);
            $table->string('description')->nullable()->default(null);
            $table->integer('paper_signatures')->nullable()->default(null);
            $table->timestamp('submission_date');
            $table->integer('page_number')->unsigned();
            $table->integer('index_on_page')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('petitions');
    }
}
