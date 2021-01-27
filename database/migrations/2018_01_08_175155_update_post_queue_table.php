<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePostQueueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('post_queue', function(Blueprint $table){
            $table->enum('ig_single', ['yes', 'no'])->default('no');
            $table->enum('ig_story', ['yes', 'no'])->default('yes');
            $table->enum('tw_status', ['yes', 'no'])->default('yes');
            $table->enum('tw_media', ['yes', 'no'])->default('yes');
            $table->enum('fb_status', ['yes', 'no'])->default('yes');
            $table->enum('fb_media', ['yes', 'no'])->default('yes');
            $table->enum('fb_link', ['yes', 'no'])->default('no');
            $table->string('video_title')->nullable();
            $table->string('link_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post_queue', function(Blueprint $table){
            $table->dropColumn('ig_single');
            $table->dropColumn('ig_story');
            $table->dropColumn('tw_status');
            $table->dropColumn('tw_media');
            $table->dropColumn('fb_status');
            $table->dropColumn('fb_media');
            $table->dropColumn('fb_link');
            $table->dropColumn('video_title');
            $table->dropColumn('link_url');
        });
    }
}
