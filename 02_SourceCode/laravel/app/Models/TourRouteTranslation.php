<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourRouteTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'locale',
        'title',
        'description',
    ];

    public function route(): BelongsTo
    {
        return $this->belongsTo(TourRoute::class, 'route_id');
    }
}
