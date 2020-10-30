<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class MovimientoSaldo extends Model
{
    use Searchable;

    protected $table = 'movimientos_saldo';
    protected $primaryKey = 'id_mov';
    protected $fillable = ['id_cliente', 'id_tarjeta', 'id_ubicacion', 'id_consumo', 'tipo', 'origen', 'monto', 'saldo_anterior', 'saldo_nuevo', 'tipo_usuario', 'email_usuario'];


    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'id_mov';
    }

	/**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	*/
	public function toSearchableArray()
	{
        $array = $this->toArray();

		return $array;
	}

    /**
     * Get the card owned by the client.
     */
    public function tarjeta()
    {
        return $this->belongsTo('App\Tarjeta', 'id_tarjeta');
    }

    /**
     * Get the card owned by the client.
     */
    public function cliente()
    {
        return $this->belongsTo('App\Cliente', 'id_cliente');
    }

    /**
     * Get the card owned by the client.
     */
    public function ubicacion()
    {
        return $this->belongsTo('App\Location', 'id_ubicacion');
	}

    /**
     * Get the card owned by the client.
     */
    public function consumo()
    {
        return $this->belongsTo('App\Consumo', 'id_consumo');
	}
}
