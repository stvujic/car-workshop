<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Workshop extends Model
{
    protected $fillable = [
        'owner_id','name', 'slug', 'city','address','phone','description','status'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}

