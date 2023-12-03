<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->integer("subject_id");
            $table->integer("trainingTypeCode");
            $table->integer("group_id");
            $table->string("group_name");
            $table->string("subject_name");
            $table->string("trainingTypeName");
            $table->integer("year_id");
            $table->integer("semester_id");
            $table->integer("isactive");
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
        Schema::dropIfExists('plans');
    }
};
