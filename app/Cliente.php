<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Cliente extends Model
{
	use Searchable;

    protected $table = 'clientes';
	protected $primaryKey = 'id_cliente';
	protected $guarded = ['add_balance', 'id_cliente', 'tarjeta'];

	protected $appends = array('mainCardNumber', 'mainCardName');

	public function getMainCardNameAttribute()
	{
		return  empty($this->mainCard) ? null : $this->mainCard->nombre;
	}

	public function getMainCardNumberAttribute()
	{
		return  empty($this->mainCard) ? null : $this->mainCard->tarjeta;
	}

	/**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'id_cliente';
    }

	/**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	*/
	public function toSearchableArray()
	{
		$array = $this->toArray();
			
		return array('nombre' => $array['nombre'], 'correo' => $array['correo']);
	}
	
	
	/**
     * Get the card owned by the client.
     */
    public function mainCard()
    {
        return $this->belongsTo('App\Tarjeta', 'id_tarjeta_principal');
	}
	

	/**
     * Get all the cards owned by the client.
     */
    public function cards()
    {
        return $this->hasMany('App\Tarjeta', 'id_cliente')->where('adicional', true);
    }
}
