<?php

use App\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'Admin Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('12345678'),
        ]);

        $admin->assignRole('admin');

    }
}
