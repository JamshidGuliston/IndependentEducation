<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string("topicname");
            $table->longtext("text");
            $table->unsignedBigInteger("test_id");
            $table->date('start_time_of_test');
            $table->date('finish_time_of_test');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('topics');
    }
};
