<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TiendaCC extends Model
{
    protected $table = 'tiendas_cc';
    protected $primaryKey = 'id_tda';
    public $incrementing = false;
	protected $fillable = ['id_tda', 'nombre', 'fecha_sync_establecimiento'];
}
