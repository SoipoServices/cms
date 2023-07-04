<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('author_id')->index();
            $table->unsignedInteger('category_id')->index()->nullable();
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->text('body');
            $table->boolean('published')->default(false);
            $table->boolean('featured')->default(false);
            $table->timestamp('scheduled_for')->useCurrent();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
