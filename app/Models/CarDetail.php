<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarDetail extends Model
{
    public function car()
    {
        return $this->belongsTo(Cars::class, 'car_id');
    }
}
