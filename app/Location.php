<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'ubicaciones';
	protected $primaryKey = 'id_ubicacion';
	protected $guarded = [];

	public function rules()
	{
		return $this->hasMany('App\Regla', 'id_ubicacion');
	}
}
