<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class RemoveNamesFromSignaturesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('signatures', function ($table) {
            $table->dropColumn(['firstname', 'lastname']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
    }
}
