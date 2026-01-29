<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'workshop_id', 'name', 'duration_minutes', 'price'
    ];

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }

}
