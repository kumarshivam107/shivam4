<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFbGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fb_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('facebook_id')->unsigned();
            $table->foreign('facebook_id')
                ->references('id')->on('facebook_accounts')
                ->onDelete('cascade');
            $table->string('group_id');
            $table->string('group_name');
            $table->enum('status', ['active', 'inactive'])->default('active');
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
        Schema::dropIfExists('fb_groups');
    }
}
