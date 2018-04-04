<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("INSERT INTO `users` (`id`, `name`, `first_name`, `last_name`,`email`, `password`, `remember_token`, `created_by`, `created_at`, `updated_by`, `updated_at`, `status`) VALUES
(1, 'root', 'Root', 'Toor', 'wamaebenson06@gmail.com', '". bcrypt('root123')."', '2cOmuI5UBgukqq1BRHuiDBLqltf0WhKVUI3NtPGvDkx1QEZQAksrXZnF0eOD', 1, '2017-12-26 05:46:35', NULL, '2018-01-23 09:58:16', 1),
(2, 'admin', 'Admin', 'Nimda', 'admin@gmail.com', '". bcrypt('admin123')."', 'sszbRnzwfiStwCwvk0l9aPVhso3ZdGxx7bd3WVIKeTUlzMaqcwh8pecz1WSQ', 1, '2018-02-12 08:37:47', NULL, '2018-02-12 08:37:47', 1),
(3, 'member', 'Member', 'Rebmem', 'member@gmail.com', '". bcrypt('member123')."', NULL, 1, '2018-02-14 08:53:12', NULL, '2018-02-14 08:53:12', 1);
");
    }
}
