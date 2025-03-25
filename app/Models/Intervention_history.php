<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervention_history extends Model
{
    use HasFactory;
    protected $fillable =[
        'intervention_id',
        'dateintervention',
        'society',
        'details',
    ];
    public function interventions()
    {
        return $this->hasMany(Intervention::class);
    }
}
