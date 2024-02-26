<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoCreditos extends Model
{
    use HasFactory;

    protected $guarded= ['id'];

    public function pagocreditable()
    {
        return $this->morphMany('App\Models\PagoCreditos', 'cashesable');
    }




}
