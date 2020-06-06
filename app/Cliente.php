<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Cliente extends Model
{
	use Searchable;

    protected $table = 'clientes';
	protected $primaryKey = 'id_cliente';
	protected $fillable = [
        'id_tarjeta_principal', 'id_ubicacion', 'nombre','rfc', 'correo', 'celular', 'sexo', 'flotilla', 'estatus', 'saldo'
    ];

	protected $appends = array('tarjeta', 'nombreTarjeta');

	public function getNombreTarjetaAttribute()
	{
		return  empty($this->mainCard) ? null : $this->mainCard->nombre;
	}

	public function getTarjetaAttribute()
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
