<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hotspot extends Model
{
    protected $fillable = [
        'scene_id',
        'artifact_id',
        'yaw',
        'pitch',
        'icon',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'yaw' => 'decimal:3',
            'pitch' => 'decimal:3',
            'sort_order' => 'integer',
        ];
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
