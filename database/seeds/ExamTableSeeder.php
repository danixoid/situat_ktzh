<?php

use Illuminate\Database\Seeder;

class ExamTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = \App\User::inRandomOrder()
            ->take(10)
            ->get();

        foreach ($users as $user) :
            $exam = new \App\Exam();
            $exam->position_id = \App\Position::inRandomOrder()->first()->id;
            $exam->user_id  = $user->id;
            $exam->chief_id = \App\User::first()->id;
            $exam->count = rand(2,5);
            $exam->save();
        endforeach;
    }
}
