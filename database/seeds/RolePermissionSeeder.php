<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $admin_permisos = [
            'config primer consumo',
            'alta ubicacion',
            'edicion ubicacion',
            'baja ubicacion',
            'alta usuario',
            'edicion usuario',
            'baja usuario',
            'reporte'
        ];
    
        $gerente_permisos = [
            'abono manual cg',
            'alta cliente',
            'edicion cliente',
            'baja cliente',
            'repocision tarjeta',
            'baja tarjeta',
            'cuentas',
            'historial saldo'
        ];
    
        $operador_permisos = [
            'alta cliente',
            'edicion cliente',
            'repocision tarjeta',
            'cuentas',
            'historial saldo'
        ];

        $admin = Role::findOrCreate('admin');
        $gerente = Role::findOrCreate('gerente');
        $operador = Role::findOrCreate('operador');

        $admin_permisos_obj = [];
        $gerente_permisos_obj = [];
        $operador_permisos_obj = [];

        foreach ($admin_permisos as $p) {
            $permiso = Permission::findOrCreate($p);
            array_push($admin_permisos_obj, $permiso);
        }

        foreach ($gerente_permisos as $p) {
            $permiso = Permission::findOrCreate($p);
            array_push($gerente_permisos_obj, $permiso);
        }

        foreach ($operador_permisos as $p) {
            $permiso = Permission::findOrCreate($p);
            array_push($operador_permisos_obj, $permiso);
        }

        $admin->syncPermissions($admin_permisos_obj);
        $gerente->syncPermissions($gerente_permisos_obj);
        $operador->syncPermissions($operador_permisos_obj);
    }
}
