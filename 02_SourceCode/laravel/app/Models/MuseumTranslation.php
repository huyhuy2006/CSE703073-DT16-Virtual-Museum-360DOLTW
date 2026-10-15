<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MuseumTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'museum_id',
        'locale',
        'name',
        'tagline',
        'description',
    ];

    public function museum(): BelongsTo
    {
        return $this->belongsTo(Museum::class);
    }
}
