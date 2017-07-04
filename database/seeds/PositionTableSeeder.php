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
        $poses = [
            'deps' => [
                'Директор',
                'Заместитель директора',
            ],
            'funct' => [
                'Руководитель группы',
                'Главный менеджер',
                'Главный менеджер 1 уровня',
                'Главный менеджер 2 уровня',
                'Главный менеджер 3 уровня',
                'Менеджер',
                'Менеджер 1 уровня',
                'Менеджер 2 уровня',
                'Менеджер 3 уровня',
                'Начальник управления',
                'Начальник отдела',
                'Руководитель',
                'Главный специалист',
                'Эксперт',
            ],
        ];

//        var_dump($poses);
        
        $roots = \App\Org::whereNull('org_id')->first()->children;

        foreach ($roots as $root) {
            foreach ($root->children as $sub) {
//                echo $sub->name;
                foreach ($poses['deps'] as $pos) {
                    $position = new \App\Position();
                    $position->name = $pos;
                    $position->org_id = $sub->id;
                    $position->save();
                }

                foreach ($sub->children as $child) {
                    foreach ($poses['funct'] as $post) {
                        $position = new \App\Position();
                        $position->name = $post;
                        $position->org_id = $child->id;
                        $position->save();
                    }
                }
            }
        }

//        foreach(\App\User::all() as $user) :
//
//            $org = \App\Org::inRandomOrder()->first();
//            $position = new \App\Position();
//            $position->name = 'Должность ' . $user->id;
//            $position->org_id = $org->id;
//            $position->save();
//        endforeach;
    }

}
