<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SitesTemperature extends Model
{
    use HasFactory;

    protected $guarded= ['id'];

    /*------------Relaciones ----------------------*/

    public function temperatures()
    {
        return $this->hasMany(Temperature::class, 'sites_temperatures_id');
    }


    /*-------------------Scopes--------------------*/

    public function scopeActive($query)
      {
           return $query->where('status', 'ACTIVE');
      }
}
