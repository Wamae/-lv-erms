<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("INSERT INTO `categories` (`id`, `category`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'Land', 1, '2017-12-26 05:46:35', NULL, '2018-01-23 09:58:16'),
(2, 'Housing', 1, '2017-12-26 05:46:35', NULL, '2018-01-23 09:58:16'),
(3, 'Planning', 1, '2017-12-26 05:46:35', NULL, '2018-01-23 09:58:16'),
(4, 'Administration', 1, '2017-12-26 05:46:35', NULL, '2018-01-23 09:58:16');
");
    }
}
