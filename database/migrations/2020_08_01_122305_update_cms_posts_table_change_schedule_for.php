<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCmsPostsTableChangeScheduleFor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->timestamp('scheduled_at')->useCurrent()->after('featured');
            $table->dropColumn('schedule_for');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->timestamp('scheduled_for')->after('featured')->useCurrent();
            $table->dropColumn('schedule_at');
        });
    }
}
