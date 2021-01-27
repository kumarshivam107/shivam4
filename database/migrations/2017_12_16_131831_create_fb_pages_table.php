<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFbPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fb_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('facebook_id')->unsigned();
            $table->foreign('facebook_id')
                ->references('id')->on('facebook_accounts')
                ->onDelete('cascade');
            $table->string('page_id');
            $table->string('page_name');
            $table->longText('page_credentials');
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
        Schema::dropIfExists('fb_pages');
    }
}
