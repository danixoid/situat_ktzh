<?php

use Illuminate\Database\Seeder;

class PositionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(\App\User::all() as $user) :

            $org = \App\Org::inRandomOrder()->first();
            $position = new \App\Position();
            $position->name = 'Должность ' . $user->id;
            $position->org_id = $org->id;
            $position->save();
        endforeach;
    }

}
