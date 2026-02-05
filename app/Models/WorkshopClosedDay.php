<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkshopClosedDay extends Model
{
    use HasFactory;
    protected $fillable = [
        'workshop_id',
        'start_date',
        'end_date',
        'reason'
    ];

    protected $casts = [
      'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function workshop(): BelongsTo
    {
        return $this->belongsTo(Workshop::class);
    }
}
