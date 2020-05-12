<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Regla extends Model
{
    protected $primaryKey = 'id_regla';

    protected $fillable = [
        'regla', 'tipo', 'estatus', 'monto', 'porcentaje', 'hora_inicial', 'hora_final', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'id_ubicacion'
    ];

    protected $appends = array('days');

    public function getDaysAttribute()
    {
        return substr(($this->lunes ? 'L,' : '') . ($this->martes ? 'M,' : '') . ($this->miercoles ? 'Mi,' : '') 
        . ($this->jueves ? 'J,' : '') . ($this->viernes ? 'V,' : '') . ($this->sabado ? 'S,' : '')
        . ($this->domingo ? 'D,' : ''), 0, -1);
    }

}
