<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class License extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'url',
        'requires_attribution',
        'share_alike',
    ];

    protected function casts(): array
    {
        return [
            'requires_attribution' => 'boolean',
            'share_alike' => 'boolean',
        ];
    }

    public function museums(): HasMany
    {
        return $this->hasMany(Museum::class);
    }
}
