<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArtifactTranslation extends Model
{
    protected $fillable = [
        'artifact_id',
        'locale',
        'name',
        'summary',
        'detail',
    ];

    public function artifact(): BelongsTo
    {
        return $this->belongsTo(Artifact::class);
    }
}
