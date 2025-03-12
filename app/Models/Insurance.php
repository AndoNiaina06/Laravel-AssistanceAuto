<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'typeinsurance',
        'startdate',
        'enddate',
        'insuranceprice',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
