<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePositionQuestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("position_quest", function (Blueprint $table) {
            $table->increments("id");
            $table->unsignedInteger("position_id");
            $table->unsignedInteger("quest_id");
            $table->timestamps();

            $table
                ->unique(['position_id','quest_id']);
            $table->foreign('position_id')
                ->references('id')->on('positions');
            $table->foreign('quest_id')
                ->references('id')->on('quests');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("position_quest");
    }
}
