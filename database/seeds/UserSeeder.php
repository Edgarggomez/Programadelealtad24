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
        $admin = User::create([
            'name' => 'Admin Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('12345678'),
        ]);

        $gerente = User::create([
            'name' => 'Gerente Gerente',
            'email' => 'gerente@test.com',
            'password' => Hash::make('12345678'),
        ]);

        $operador = User::create([
            'name' => 'Operador Operador',
            'email' => 'operador@test.com',
            'password' => Hash::make('12345678'),
        ]);

        $admin->assignRole('admin');
        $gerente->assignRole('gerente');
        $operador->assignRole('operador');
    }
}
