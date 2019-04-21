<?php

use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $database = file_get_contents(base_path('database/seeds')."/region.sql");

        DB::connection()->getPdo()->exec($database);
    }
}
