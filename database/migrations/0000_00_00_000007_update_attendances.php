<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAttendances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(config('amethyst.attendance.data.attendance.table'), function (Blueprint $table) {
            $table->integer('type_id')->nullable()->unsigned();
            $table->foreign('type_id')->references('id')->on(config('amethyst.taxonomy.data.taxonomy.table'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
