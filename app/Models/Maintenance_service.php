<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance_service extends Model
{
    use HasFactory;
    protected $fillable =[
        'car_id',
        'typeservice',
        'dateservice',
        'cout',
        'society',
        'status',

    ];
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
