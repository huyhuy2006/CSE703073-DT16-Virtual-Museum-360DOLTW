<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Scene extends Model
{
    protected $fillable = [
        'museum_id',
        'slug',
        'tile_path',
        'preview_path',
        'panorama_type',
        'width_px',
        'height_px',
        'file_size_kb',
        'initial_yaw',
        'initial_pitch',
        'initial_hfov',
        'floor_label',
        'map_x',
        'map_y',
        'sort_order',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'width_px' => 'integer',
            'height_px' => 'integer',
            'file_size_kb' => 'integer',
            'initial_yaw' => 'decimal:3',
            'initial_pitch' => 'decimal:3',
            'initial_hfov' => 'decimal:2',
            'map_x' => 'decimal:2',
            'map_y' => 'decimal:2',
            'sort_order' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    public function museum(): BelongsTo
    {
        return $this->belongsTo(Museum::class);
    }

    public function outgoingGateways(): HasMany
    {
        return $this->hasMany(Gateway::class, 'from_scene_id');
    }

    public function incomingGateways(): HasMany
    {
        return $this->hasMany(Gateway::class, 'to_scene_id');
    }

    public function hotspots(): HasMany
    {
        return $this->hasMany(Hotspot::class);
    }

    public function audioTracks(): HasMany
    {
        return $this->hasMany(AudioTrack::class);
    }

    public function tourSteps(): HasMany
    {
        return $this->hasMany(TourRouteStep::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(SceneTranslation::class);
    }}
