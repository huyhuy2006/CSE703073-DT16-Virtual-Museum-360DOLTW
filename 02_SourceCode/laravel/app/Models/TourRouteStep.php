<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourRouteStep extends Model
{
    protected $fillable = [
        'route_id',
        'step_no',
        'scene_id',
        'artifact_id',
        'dwell_sec',
        'focus_yaw',
        'focus_pitch',
    ];

    protected function casts(): array
    {
        return [
            'step_no' => 'integer',
            'dwell_sec' => 'integer',
            'focus_yaw' => 'decimal:3',
            'focus_pitch' => 'decimal:3',
        ];
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(TourRoute::class, 'route_id');
    }

    public function scene(): BelongsTo
    {
        return $this->belongsTo(Scene::class);
    }

    public function artifact(): BelongsTo
    {
        return $this->belongsTo(Artifact::class);
    }
}
