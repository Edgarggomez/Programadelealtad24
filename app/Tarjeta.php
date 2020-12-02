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
        'id_cliente', 'tarjeta', 'nombre','adicional', 'estatus', 'fecha_sync_update_tarjeta'
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

        $card = Tarjeta::where('tarjeta',$attributes['tarjeta'])->first();
        $oldCard = Tarjeta::where([['id_cliente', $attributes['id_cliente']], ['adicional', false], ['estatus', '1'], ['tarjeta', '<>', $attributes['tarjeta']]])->first();

        if(!$card) {
            $tarjetaCC = TarjetaCC::where('tarjeta', $attributes['tarjeta'])->first();
            if($tarjetaCC){
                $attributes['fecha_sync_update_tarjeta'] = $tarjetaCC->fecha_sync_nueva_tarjeta;
                $card = Tarjeta::create($attributes);
            }
        }

        if ($oldCard && !$attributes['adicional']) {
            $oldCard->adicional = null;
            $oldCard->estatus = false;
            $oldCard->save();
        }

        return $card;
    }


}
