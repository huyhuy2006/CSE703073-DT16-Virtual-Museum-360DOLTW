<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AudioTrack extends Model
{
    protected $fillable = [
        'artifact_id',
        'scene_id',
        'locale',
        'file_path',
        'duration_sec',
        'narrator',
        'transcript',
    ];

    protected function casts(): array
    {
        return [
            'duration_sec' => 'integer',
        ];
    }

    public function artifact(): BelongsTo
    {
        return $this->belongsTo(Artifact::class);
    }

    public function scene(): BelongsTo
    {
        return $this->belongsTo(Scene::class);
    }
}
