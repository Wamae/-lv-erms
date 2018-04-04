<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::statement("INSERT INTO `permissions` (`id`, `module_id`, `name`, `guard_name`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 1, 'add user', 'web', 1, '2018-02-13 05:15:39', NULL, '2018-02-13 05:15:39'),
(2, 1, 'edit user', 'web', 1, '2018-02-13 05:15:51', NULL, '2018-02-13 05:15:51'),
(3, 1, 'view users', 'web', 1, '2018-02-13 05:16:11', NULL, '2018-02-13 05:16:11'),
(4, 2, 'add role', 'web', 1, '2018-02-13 05:16:26', NULL, '2018-02-13 05:16:26'),
(5, 2, 'edit role', 'web', 1, '2018-02-13 05:16:32', NULL, '2018-02-13 05:16:32'),
(6, 2, 'remove role', 'web', 1, '2018-02-13 05:16:45', NULL, '2018-02-13 05:16:45'),
(7, 2, 'view roles', 'web', 1, '2018-02-13 06:10:55', NULL, '2018-02-13 06:10:55'),
(8, 3, 'add permission', 'web', 1, '2018-02-08 05:46:22', 1, '2018-02-13 05:17:04'),
(9, 3, 'edit permission', 'web', 1, '2018-02-13 05:18:03', NULL, '2018-02-13 05:18:03'),
(10, 3, 'view permissions', 'web', 1, '2018-02-13 05:17:45', NULL, '2018-02-13 05:17:45'),
(11, 4, 'add document', 'web', 1, '2018-01-22 10:57:35', NULL, '2018-01-22 10:57:35'),
(12, 4, 'edit document', 'web', 1, '2018-02-08 05:44:59', 1, '2018-02-13 05:12:56'),
(17, 4, 'view documents', 'web', 1, '2018-01-22 10:57:35', NULL, '2018-01-22 10:57:35'),
(13, 5, 'add category', 'web', 1, '2018-01-22 10:57:35', NULL, '2018-01-22 10:57:35'),
(14, 5, 'edit category', 'web', 1, '2018-01-22 10:57:35', NULL, '2018-01-22 10:57:35'),
(19, 5, 'view categories', 'web', 1, '2018-01-22 10:57:35', NULL, '2018-01-22 10:57:35'),
(15, 6, 'add sub category', 'web', 1, '2018-01-22 10:57:35', NULL, '2018-01-22 10:57:35'),
(16, 6, 'edit sub category', 'web', 1, '2018-01-22 10:57:35', NULL, '2018-01-22 10:57:35'),
(18, 6, 'view sub categories', 'web', 1, '2018-01-22 10:57:35', NULL, '2018-01-22 10:57:35')

;");
    }

}
