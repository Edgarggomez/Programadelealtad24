<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Cliente extends Model
{
	use Searchable;

    protected $table = 'clientes';
	protected $primaryKey = 'id_cliente';
	protected $guarded = ['add_balance', 'id_cliente'];

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

	
}
