<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstagramAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instagram_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('credentials');
            $table->string('profile_name');
            $table->string('profile_id');
            $table->string('profile_picture')->nullable();
            $table->integer('followers')->nullable();
            $table->integer('following')->nullable();
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
        Schema::dropIfExists('instagram_accounts');
    }
}
