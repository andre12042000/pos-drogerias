<?php

namespace Modules\Mantenimiento\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoEquipo extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $guarded= ['id'];
    protected static function newFactory()
    {
        return \Modules\Mantenimiento\Database\factories\TipoEquipoFactory::new();
    }
    public function equipos()
    {
        return $this->hasMany(Equipos::class);
    }
}
