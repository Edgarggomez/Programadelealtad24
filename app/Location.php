<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Location extends Model
{
	use Searchable;

    protected $table = 'ubicaciones';
	protected $primaryKey = 'id_ubicacion';
	protected $guarded = [];

	public function rules()
	{
		return $this->hasMany('App\Regla', 'id_ubicacion');
	}

	/**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	*/
	public function toSearchableArray()
	{
		$array = $this->toArray();
			
		return array('ubicacion' => $array['ubicacion']);
	}
}
