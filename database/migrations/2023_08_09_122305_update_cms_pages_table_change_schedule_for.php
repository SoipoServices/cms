<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCmsPagesTableChangeScheduleFor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('pages', function (Blueprint $table) {
            $table->timestamp('scheduled_at')->useCurrent()->nullable()->after('published');
            $table->dropColumn('scheduled_for');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->timestamp('scheduled_for')->useCurrent()->after('published');
            $table->dropColumn('scheduled_at');
        });
    }
}
