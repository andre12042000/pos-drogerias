<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $guarded= ['id'];

    public function getRouteKeyName()
    {
        return 'code';
    }

    //relaciones

    public function inventario()
    {
        return $this->hasOne(Inventario::class);
    }

    public function laboratorio()
    {
        return $this->belongsTo(Laboratorio::class);
    }

    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function presentacion()
    {
        return $this->belongsTo(Presentacion::class, 'presentacion_id');
    }


    //retornar el nombre del producto en mayusculas
    public function getProductoAttribute()
    {
        return strtoupper($this->name);
    }
    //retornar precio formateado

    public function getClienteAttribute()
    {
        return '$' . number_format($this->precio_caja, 0);
    }

    public function getTecnicoAttribute()
    {
        return '$' . number_format($this->precio_blister, 0);
    }

    public function getDistribuidorAttribute()
    {
        return '$' . number_format($this->precio_unidad, 0);
    }



    //scopes

    public function scopeActive($query)
    {
        return $query->where('status', 'ACTIVE');
    }

    public function scopeSearch($query, $search)
    {
        if(strlen($search) > 0){
            return $query->where(DB::raw('concat_ws(" ", code, name)'), 'like', "%" . $search . "%");
        }else{
            return $query;
        }
    }

    public function scopeStatus($query, $estado)
    {
        if(strlen($estado) > 0){
            return $query->where('status', $estado);
        }else{
            return $query;
        }
    }

    public function scopeCategory($query, $category)
    {
        if(strlen($category) > 0){
            return $query->where('category_id', $category);
        }else{
            return $query;
        }
    }

}
