<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Orders extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    const solicitado      = 1;
    const entregado       = 2;

    public function getStatusprocesoAttribute()
    {

        switch ($this->status) {
            case 1:
                $metodo = 'Solicitado';
                break;
            case 2:
                $metodo = 'Entregado';
                break;
        }

        return $metodo;
    }

    public function getRouteKeyName()
    {
        return 'full_nro';
    }

    public function getFragmentoAttribute()
    {
        if (!is_null($this->descripcion)) {
            if (strlen($this->descripcion) > 50) {
                $framento = substr($this->descripcion, 0, 50) . ' . . .';
            } else {
                $framento = $this->descripcion;
            }


            return $framento;
        }
    }

    //relacion polimorfica con cash

    public function cashs()
    {
        return $this->morphMany('App\Models\Cash', 'cashesable');
    }

    public function abonos()
    {
        return $this->morphMany('App\Models\Abono', 'abonable');
    }

    /*relaciones */

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function asignado()
    {
        return $this->belongsTo(User::class, 'assigned');
    }

    public function details()
    {
        return $this->hasMany(OrdersDetails::class, 'order_id');
    }


    public function orderscomments()
    {
        return $this->hasMany(OrderComentario::class);
    }

    /*Scopes */

    public function scopeSearch($query, $search)
    {
        if (strlen($search) > 0) {
            return $query->where('descripcion', 'like', "%" . $search . "%");
        } else {
            return $query;
        }
    }
    public function scopeEstado($query, $filter_estado)
    {
        if (strlen($filter_estado) > 0) {
            return $query->where('status', $filter_estado);
        } else {
            return $query;
        }
    }
    public function scopeProvedor($query, $filter_proveedor)
    {
        if (strlen($filter_proveedor) > 0) {
            return $query->where('client_id', 'like', "%" . $filter_proveedor . "%");
        } else {
            return $query;
        }
    }
    public function scopeAsignado($query, $filter_asignado)
    {
        if ($filter_asignado != 'null' && $filter_asignado != '' ) {
            return $query->where('assigned', $filter_asignado);
        } elseif ($filter_asignado == 'null') {
            return $query->whereNull('assigned');


        } else {

            return $query;
        }
    }
}
