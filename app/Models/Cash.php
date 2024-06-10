<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cash extends Model
{
    use HasFactory;

    protected $guarded= ['id'];

    //relacion polimorfica

      public function cashesable()
      {
          return $this->morphTo();
      }
      public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'cashesable_id');
    }

    public function abono()
    {
        return $this->belongsTo(Abono::class, 'cashesable_id');
    }

    public function scopeCajero($query, $user)
    {
        if (strlen($user) > 0) {
            return $query->where('user_id', $user);
        } else {
            return $query;
        }
    }

    public function saleFilteredByCajero($user)
{
    return $this->hasOne(Sale::class, 'cashesable_id')
                ->whereHas('user', function ($query) use ($user) {
                    $query->cajero($user);
                });
}

public function busqueda($user)
{
    return $this->sale()->whereHas('user', function($query) use ($user) {
        $query->cajero($user);
    });
}


}
