<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Scout\Searchable;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable, HasRoles;
    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','id_ubicacion', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['role'];

    public function getRoleAttribute()
    {
        return $this->getRoleNames()->first();
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('status', function (Builder $builder) {
            $builder->where('status', '<>', '2');
        });
    }

    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	*/
	public function toSearchableArray()
	{
		$array = $this->toArray();
			
		return array('name' => $array['name'], 'email' => $array['email']);
	}
}
