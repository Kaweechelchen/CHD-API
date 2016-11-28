<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class LongtextEscriptionsOnPetitions extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('petitions', function ($table) {
            $table->longtext('description')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
    }
}
