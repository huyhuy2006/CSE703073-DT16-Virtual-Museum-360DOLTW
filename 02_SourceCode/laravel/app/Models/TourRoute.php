<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TourRoute extends Model
{
    protected $fillable = [
        'slug',
        'theme',
        'estimated_minutes',
        'is_published',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'estimated_minutes' => 'integer',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function steps(): HasMany
    {
        return $this->hasMany(TourRouteStep::class, 'route_id')
            ->orderBy('step_no');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(TourRouteTranslation::class, 'route_id');
    }
}
