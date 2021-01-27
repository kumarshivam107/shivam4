<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostQueueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_queue', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('msg_body');
            $table->string('instagram_ids')->nullable();
            $table->string('facebook_ids')->nullable();
            $table->string('twitter_ids')->nullable();
            $table->string('fb_group_ids')->nullable();
            $table->string('fb_page_ids')->nullable();
            $table->timestamp('schedule_time');
            $table->string('image_file')->nullable();
            $table->string('video_file')->nullable();
            $table->enum('status', ['active', 'inactive', 'completed', 'failed'])->default('active');
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
        Schema::dropIfExists('post_queque');
    }
}
