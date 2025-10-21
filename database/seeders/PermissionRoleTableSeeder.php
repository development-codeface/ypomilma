<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionRoleTableSeeder extends Seeder
{
    public function run()
    {
         DB::table('permission_role')->insert([
            ['role_id' => 1, 'permission_id'=>'1'],
            ['role_id' => 1, 'permission_id'=>'2'],
            ['role_id' => 1, 'permission_id'=>'3'],
            ['role_id' => 1, 'permission_id'=>'4'],
        ]);
    }
}
