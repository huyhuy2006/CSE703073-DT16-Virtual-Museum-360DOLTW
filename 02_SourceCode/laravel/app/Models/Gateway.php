<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gateway extends Model
{
    protected $fillable = [
        'from_scene_id',
        'to_scene_id',
        'yaw',
        'pitch',
        'target_yaw',
        'transition_ms',
    ];

    protected function casts(): array
    {
        return [
            'yaw' => 'decimal:3',
            'pitch' => 'decimal:3',
            'target_yaw' => 'decimal:3',
            'transition_ms' => 'integer',
        ];
    }

    public function fromScene(): BelongsTo
    {
        return $this->belongsTo(Scene::class, 'from_scene_id');
    }

    public function toScene(): BelongsTo
    {
        return $this->belongsTo(Scene::class, 'to_scene_id');
    }
}
