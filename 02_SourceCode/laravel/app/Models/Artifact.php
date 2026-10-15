<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Artifact extends Model
{
    protected $fillable = [
        'museum_id',
        'code',
        'category',
        'period_label',
        'origin',
        'image_path',
        'source_note',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function museum(): BelongsTo
    {
        return $this->belongsTo(Museum::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ArtifactTranslation::class);
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
}
