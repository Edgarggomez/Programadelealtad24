<?php

use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\BdCC', 5)->create();
        factory('App\Location', 5)->create();
    }
}
