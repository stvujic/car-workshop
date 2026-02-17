<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    public const STATUS_APPROVED = 'approved';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_PENDING = 'pending';

    use HasFactory;

    protected $fillable = [
        'workshop_id','user_id','date','time','status','note'
    ];

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
