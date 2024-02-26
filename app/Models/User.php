<?php

namespace App\Models;

use App\Models\Orders;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','status','photo'
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

    //relaciones

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    //relaciones

    public function orderservices()
    {
        return $this->hasMany(ServiceOrder::class);
    }
    public function historial()
    {
        return $this->hasMany(HistorialAsiganacion::class);
    }

    public function orderservicesasignado()
    {
        return $this->hasMany(Orders::class);
    }

    public function orderscomments()
    {
        return $this->hasMany(OrderComentario::class);
    }

    public function credits()
    {
        return $this->hasMany(Credit::class);
    }

    //scopes

    public function scopeSearch($query, $search)
    {
        if(strlen($search) > 0){
            return $query->where(DB::raw('concat_ws(" ", name, email)'), 'like', "%" . $search . "%");
        }else{
            return $query;
        }
    }

    public function scopeEstado($query, $estado)
    {
        if(strlen($estado) > 0){
            return $query->where('status', $estado);
        }else{
            return $query;
        }
    }

}
