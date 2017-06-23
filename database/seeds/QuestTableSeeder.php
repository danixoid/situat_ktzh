<?php

use Illuminate\Database\Seeder;

class QuestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\User::first();

        foreach(\App\Position::all() as $position) :
            for($i = 0; $i < 5; $i++):
                $quest = new \App\Quest();
                $quest->position_id = $position->id;
                $quest->author_id = $user->id;
                $quest->source = "Исходные денные №$i для $position->orgPath $position->name, составитель $user->name";
                $quest->task = "Задание №$i для $position->orgPath $position->name, составитель $user->name";
                $quest->timer = 5;
                $quest->save();
            endfor;
        endforeach;
    }
}
