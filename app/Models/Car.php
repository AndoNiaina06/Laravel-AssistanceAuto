<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    protected $fillable =[
        'user_id',
        'carname',
        'immatriculation',
        'marque',
    ];
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
