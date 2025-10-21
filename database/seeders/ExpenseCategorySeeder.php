<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpenseCategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('expense_categories')->insert([
            ['name' => 'Fuel'],
            ['name' => 'Repair'],
            ['name' => 'Feed Purchase'],
        ]);
    }
}
