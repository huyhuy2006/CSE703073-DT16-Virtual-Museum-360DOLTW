<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Museum extends Model
{
    protected $fillable = [
        'slug',
        'country_code',
        'city',
        'lat',
        'lng',
        'license_id',
        'author_credit',
        'source_url',
        'source_note',
        'is_published',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'lat' => 'decimal:7',
            'lng' => 'decimal:7',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }

    public function scenes(): HasMany
    {
        return $this->hasMany(Scene::class);
    }

    public function artifacts(): HasMany
    {
        return $this->hasMany(Artifact::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(MuseumTranslation::class);
    }
}
