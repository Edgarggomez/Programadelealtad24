<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Micky Mouse',
            'email' => 'micky@mouse.com',
            'status' => 'a',
            'password' => Hash::make('12345678'),
        ]);
        $user->assignRole('admin');
    }
}
