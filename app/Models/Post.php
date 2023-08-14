<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, CascadesDeletes;

    protected $cascadeDeletes = [
      'comments',
      'likes',
        'notification'
    ];

    protected $fillable = [
        'user_id',
        'description',
        'post_images'
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function likes(): MorphMany {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function comments(): MorphMany {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function notification(): MorphOne {
        return $this->morphOne(Notification::class, 'notificationable');
    }

    public function saveds(): MorphMany {
        return $this->morphMany(Saved::class, 'saveable');
    }
}
