<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TiendaCC extends Model
{
    protected $table = 'tiendas_cc';
	protected $primaryKey = 'id_tda';
	protected $fillable = ['id_tda', 'name', 'fecha_sync_establecimiento'];
}
