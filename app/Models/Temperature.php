<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temperature extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /*----------------------Relaciones ------------------------*/


    public function sitio()
    {
        return $this->belongsTo(SitesTemperature::class, 'sites_temperatures_id');
    }

}
