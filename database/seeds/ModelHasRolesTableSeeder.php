<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModelHasRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("INSERT INTO `model_has_roles` (`role_id`, `model_id`, `model_type`) VALUES (1, 1, 'App\\User'),
 (4, 1, 'App\\User')
;");
    }
}
