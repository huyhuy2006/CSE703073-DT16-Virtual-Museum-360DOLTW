<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SceneTranslation extends Model
{
    protected $fillable = [
        'scene_id',
        'locale',
        'title',
        'description',
    ];

    public function scene(): BelongsTo
    {
        return $this->belongsTo(Scene::class);
    }
}
