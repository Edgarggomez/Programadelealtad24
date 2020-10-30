<?php

namespace App;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

class Tarjeta extends Model
{
    use Searchable;
    protected $table = 'tarjetas';
	protected $primaryKey = 'id_tarjeta';
	protected $fillable = [
        'id_cliente', 'tarjeta', 'nombre','adicional', 'estatus'
    ];

	/**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'id_tarjeta';
    }

	/**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	*/
	public function toSearchableArray()
	{
		$array = $this->toArray();

		return array('tarjeta' => $array['tarjeta']);
	}

	public static function createOrUpdate($attributes) {
		$card = Tarjeta::where('tarjeta', $attributes['tarjeta'])->first();
		$mainCard = Tarjeta::where([['id_cliente', $attributes['id_cliente']], ['adicional', false]])->first();
		if (!empty($mainCard) && !$attributes['adicional']) {
			$mainCard->adicional = true;
			$mainCard->save();
		}
		if (empty($card)) {
			$card = Tarjeta::create($attributes);
		} else {
			if($card->id_cliente == null || $card->id_cliente == $attributes['id_cliente']) {
				$card->id_cliente = $attributes['id_cliente'];
				$card->adicional = $attributes['adicional'];
				$card->save();
			} else {
				return null;
			}
		}
		return $card;
    }


}
