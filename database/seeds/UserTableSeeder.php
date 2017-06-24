<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_employee = Role::where("name", "employee")->first();
        $role_manager  = Role::where("name", "manager")->first();
        $role_admin  = Role::where("name", "admin")->first();


        $admin = new User();
        $admin->name = "Данияр Карияевич";
        $admin->email = "admin@example.com";
        $admin->password = bcrypt("12345");
        $admin->save();
        
        $admin->roles()->attach($role_admin);

        $manager = new User();
        $manager->name = "Марья Валильевна";
        $manager->email = "manager@example.com";
        $manager->password = bcrypt("12345");
        $manager->save();

        $manager->roles()->attach($role_manager);


        for($i = 0; $i < 20; $i++) :
            $employee = new User();
            $employee->name = "Иван" . $i . " Иванов" . $i;
            $employee->email = "employee" . $i . "@example.com";
            $employee->password = bcrypt("12345" . $i);
            $employee->save();

            $employee->roles()->attach($role_employee);
        endfor;
    }
}
