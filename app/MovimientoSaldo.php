<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MovimientoSaldo extends Model
{
    protected $table = 'movimientos_saldo';
    protected $primaryKey = 'id_mov';
    protected $fillable = ['id_cliente', 'id_tarjeta', 'tipo', 'origen', 'monto', 'saldo_anterior', 'saldo_nuevo', 'tipo_usuario', 'email_usuario'];
}
