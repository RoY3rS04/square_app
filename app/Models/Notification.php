<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
      'notificationable_id',
      'notificationable_type'
    ];

    public function notificationable(): MorphTo {
        return $this->morphTo();
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
