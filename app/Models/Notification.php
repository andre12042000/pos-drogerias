<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table    = 'notifications';

    protected $fillable = ['read_at'];

    use HasFactory;

    /* Scopes */

    public function scopeStatus($query, $status)
    {
        if($status == 'no_leidas')
        {
            return $query->whereNull('read_at');
        }elseif($status == 'leidas'){
            return $query->whereNotNull('read_at');
        }else{
            return $query;
        }
    }


}
