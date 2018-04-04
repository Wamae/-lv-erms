<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModulesTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::statement("INSERT INTO `modules` (`id`, `module`) VALUES
(3, 'Permissions'),
(4, 'Documents'),
(5, 'Categories'),
(6, 'Sub Categories'),
(2, 'Roles'),
(1, 'Users');");
    }

}
