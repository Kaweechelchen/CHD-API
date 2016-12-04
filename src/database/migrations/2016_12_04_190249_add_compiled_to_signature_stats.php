<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompiledToSignatureStats extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('signature_stats', function (Blueprint $table) {
            $table->string('compiled')->nullable()->default(null)->after('label');
            $table->integer('delta')->nullable()->default(null)->change();
            $table->integer('count')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('signature_stats', function (Blueprint $table) {
            $table->dropColumn('compiled');
        });
    }
}
