<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Saved extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'saveable_id',
        'saveable_type'
    ];

    public function saveable(): MorphTo {
        return $this->morphTo();
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
