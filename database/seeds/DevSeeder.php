<?php

use App\BdCC;
use Illuminate\Database\Seeder;

class DevSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        //$this->call(LocationSeeder::class);
        BdCC::create([
            'nombre' => 'CCPruebas',
            'bd' => 'CCPruebas',
            'estatus' => '1'
        ]);
    }
}
