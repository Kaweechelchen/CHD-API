<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAuthorsColumnToPetitions extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('petitions', function (Blueprint $table) {
            $table->longtext('authors')->nullable()->default(null)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('petitions', function (Blueprint $table) {
            $table->dropColumn('authors');
        });
    }
}
