<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vencimientos extends Model
{
    use HasFactory;

    protected $guarded= ['id'];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'ACTIVE');
    }
    public function scopeSearch($query, $buscar)
       {
           if(strlen($buscar) > 0){
               return $query->where('lote',  $buscar);
           }else{
               return $query;
           }
       }

       public function scopeEstado($query, $status)
       {

           if(strlen($status) > 0){
               return $query->where('status',  $status);
           }else{
               return $query;
           }
       }

       public function scopeFiltroSeis($query, $seismeses)
       {

        $hoy = Carbon::now();
        $tres = $hoy->copy()->addMonths(3);
        $seis = $hoy->copy()->addMonths(6);
        $hoy = $hoy->format('Y-m-d');
           if($seismeses == true){
               return $query->whereBetween('fecha_vencimiento', [$tres, $seis]);

           }else{
               return $query;
           }


       }

       public function scopeFiltroYear($query, $mayoryear)
       {

        $hoy = Carbon::now();
        $tres = $hoy->copy()->addMonths(3);
        $seis = $hoy->copy()->addMonths(6);
        $hoy = $hoy->format('Y-m-d');
           if($mayoryear == true){
               return $query->where('fecha_vencimiento', '>',  $seis);
           }else{
               return $query;
           }


       }

       public function scopeFiltroTres($query, $tresmeses)
       {

        $hoy = Carbon::now();
        $tres = $hoy->copy()->addMonths(3);
        $seis = $hoy->copy()->addMonths(6);
        $hoy = $hoy->format('Y-m-d');
           if($tresmeses == true){
               return $query ->whereBetween('fecha_vencimiento', [$hoy, $tres]);
           }else{
               return $query;
           }


       }


}
