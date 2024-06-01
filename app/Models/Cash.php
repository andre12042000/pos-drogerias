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
}
