<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Builder;

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
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('status', function (Builder $builder) {
            $builder->where('estatus', '1');
        });
    }


    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'id_ubicacion';
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
