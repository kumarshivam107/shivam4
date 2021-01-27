<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateIgUnfollowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ig_unfollows', function(Blueprint $table){
            $table->string('exception')->nullable();
            $table->enum('exclude_verified', ['yes', 'no'])->default('no');
            $table->enum('exclude_non_verified', ['yes', 'no'])->default('no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ig_unfollows', function (Blueprint $table) {
            $table->dropColumn('exception');
            $table->dropColumn('exclude_verified');
            $table->dropColumn('exclude_non_verified');
        });
    }
}
