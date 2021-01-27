<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePictureQueueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('picture_queue', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image_file');
            $table->string('instagram_ids')->nullable();
            $table->string('facebook_ids')->nullable();
            $table->string('twitter_ids')->nullable();
            $table->timestamp('schedule_time');
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
        Schema::dropIfExists('picture_queue');
    }
}
