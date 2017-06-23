<?php

use Illuminate\Database\Seeder;

class OrgTableSeeder extends Seeder
{

    protected $max = 100;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->setOrgs(0,0);
    }

    private function setOrgs($org_id,$iter){

        for($i = 0; $i < 2; $i++) :
            $org = new \App\Org();
            $org->name = 'Структура ' . $org_id;

            if($org_id > 0) :
                $org->org_id = $org_id;
            endif;
            $org->save();

            if($iter < 3) {
                $next = $iter + 1;
                $this->setOrgs($org->id,$next);
            }

        endfor;
    }
}
