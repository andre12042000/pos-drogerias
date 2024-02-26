<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    use HasFactory;

    protected $guarded= ['id'];

    /*--------------Relaciones --------------------------*/

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }






}
