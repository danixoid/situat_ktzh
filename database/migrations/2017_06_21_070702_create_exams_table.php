<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('position_id');
            $table->unsignedInteger('chief_id');
            $table->unsignedTinyInteger('count'); //количество заданий
            $table->unsignedTinyInteger('mark'); //оценка за все задания
            $table->text('note')->nullable(); //комментарий руководителя
            $table->timestamps();

            $table->foreign('position_id')
                ->references('id')->on('positions');
            $table->foreign('user_id')
                ->references('id')->on('users');
            $table->foreign('chief_id')
                ->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exams');
    }
}
