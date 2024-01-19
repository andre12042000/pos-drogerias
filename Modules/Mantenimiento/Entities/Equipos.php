<?php

namespace Modules\Mantenimiento\Entities;

use App\Models\Brand;
use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Modules\Mantenimiento\Entities\TipoEquipo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Equipos extends Model
{
    use HasFactory;

    protected $guarded= ['id'];


    protected static function newFactory()
    {
        return \Modules\Mantenimiento\Database\factories\EquiposFactory::new();
    }

    public function scopeSearch($query, $search)
    {
        if(strlen($search) > 0){
            return $query->where(DB::raw('concat_ws(" ", referencia, marca)'), 'like', "%" . $search . "%");
        }else{
            return $query;
        }
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function tipoequipo()
    {
        return $this->belongsTo(TipoEquipo::class, 'tipo_equipo_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
