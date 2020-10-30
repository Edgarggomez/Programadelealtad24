<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Regla extends Model
{
    protected $primaryKey = 'id_regla';

    protected $fillable = [
        'regla', 'tipo', 'estatus', 'monto', 'porcentaje', 'hora_inicial', 'hora_final', 'dias', 'id_ubicacion'
    ];

    protected $casts = [
        'dias' => 'array',
    ];

    protected $appends = array('days');

    public function getDaysAttribute()
    {
        $days_array = ['D', 'L', 'M', 'Mi', 'J', 'V', 'S'];
        $display_days = [];
        foreach($this->dias as $day) {
            $display_days[] = $days_array[$day];
        }
        return implode(', ', $display_days);
    }

}
