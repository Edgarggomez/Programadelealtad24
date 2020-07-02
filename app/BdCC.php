<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BdCC extends Model
{
    protected $table = 'bds_cc';
	protected $primaryKey = 'id_bd';
	protected $fillable = ['nombre', 'bd', 'estatus', 'last_message', 'ultima_conexion', 'fecha_actualizacion', 'fecha_sync_bd'];
}
