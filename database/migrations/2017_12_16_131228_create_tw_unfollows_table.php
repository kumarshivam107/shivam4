<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTwUnfollowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tw_unfollows', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('twitter_id')->unsigned();
            $table->foreign('twitter_id')
                ->references('id')->on('twitter_accounts')
                ->onDelete('cascade');
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
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
        Schema::dropIfExists('tw_unfollows');
    }
}
