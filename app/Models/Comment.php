<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;

class Comment extends Model
{
    use HasFactory, CascadesDeletes;

    protected $cascadeDeletes = [
        'notification'
    ];

    protected $fillable = [
        'user_id',
        'comment',
        'commentable_id',
        'commentable_type'
    ];

    public function user() :BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function commentable() :MorphTo {
        return $this->morphTo();
    }

    public function notification(): MorphOne {
        return $this->morphOne(Notification::class, 'notificationable');
    }
}
