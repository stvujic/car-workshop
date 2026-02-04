<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkshopWorkingHour extends Model
{
    use HasFactory;
    protected $fillable = [
      'workshop_id',
      'day_of_week',
      'start_time',
      'end_time',
      'is_active',
    ];

    protected $casts = [
        //brisao sam cast za start i end time jel me je blokiralo kod old
        'is_active' => 'boolean',
    ];


    public function workshop(): BelongsTo
    {
        return $this->belongsTo(Workshop::class);
    }
}
