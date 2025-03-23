<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Insurance extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'car_id',
        'typeinsurance',
        'startdate',
        'enddate',
        'status',
        'insuranceprice',
    ];

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function car(): belongsTo
    {
        return $this->belongsTo(Car::class);
    }
}
