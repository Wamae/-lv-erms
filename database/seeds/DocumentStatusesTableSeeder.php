<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("INSERT INTO `document_statuses` (`id`, `status`,`created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'Active', 1, '2017-12-26 05:46:35', NULL, '2018-01-23 09:58:16'),
(2, 'Archived', 1, '2017-12-26 05:46:35', NULL, '2018-01-23 09:58:16');
");
    }
}
