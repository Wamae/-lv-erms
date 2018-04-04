<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::statement("INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
            (1, 'root', 'web', 1, '2018-02-13 13:56:43', 1, '2018-02-13 10:56:43'),
(2, 'admin', 'web', 1, '2018-02-12 11:58:58', 1, '2018-02-12 08:58:58'),
(3, 'member', 'web', 1, '2018-01-23 09:44:08', NULL, '2018-01-23 09:44:08'),
(4, 'registrar', 'web', 1, '2018-01-23 09:44:08', NULL, '2018-01-23 09:44:08')");
    }

}
