<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
	protected $primaryKey = 'id_cliente';
	protected $guarded = ['add_balance', 'id_cliente'];

	
}
