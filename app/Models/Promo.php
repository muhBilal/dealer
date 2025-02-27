<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $table = 'promos';
    protected $fillable = [
        'car_detail_id',
        'harga_awal',
        'harga_promo',
        'status',
        'end_date',
    ];

    public function carDetail()
    {
        return $this->belongsTo(CarDetail::class);
    }
}
