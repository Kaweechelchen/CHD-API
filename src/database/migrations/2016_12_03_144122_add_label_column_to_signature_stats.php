<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLabelColumnToSignatureStats extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('signature_stats', function (Blueprint $table) {
            $table->string('label')->nullable()->default(null)->after('count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('signature_stats', function (Blueprint $table) {
            $table->dropColumn('label');
        });
    }
}
