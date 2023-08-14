<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable;
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'location',
        'workplace',
        'relation',
        'description',
        'website'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function posts(): HasMany {
        return $this->hasMany(Post::class);
    }

    public function friendOf(): BelongsToMany {
        return $this->belongsToMany(static::class, 'friends', 'friend_id', 'user_id')->withTimestamps();
    }

    public function friendsOfMine(): BelongsToMany {
        return $this->belongsToMany(static::class, 'friends', 'user_id', 'friend_id')->withTimestamps();
    }

    public function likes(): HasMany {
        return $this->hasMany(Like::class);
    }

    public function notifications(): HasMany {
        return $this->hasMany(Notification::class);
    }

    public function comments(): HasMany {
        return $this->hasMany(Comment::class);
    }

    public function followers(): BelongsToMany {
        return $this->belongsToMany(static::class, 'followers', 'followed_id', 'follower_id')->withTimestamps();
    }

    public function following(): BelongsToMany {
        return $this->belongsToMany(static::class, 'followers', 'follower_id', 'followed_id')->withTimestamps();
    }

    public function saveds(): HasMany {
        return $this->hasMany(Saved::class);
    }

    public function stopPostsFrom(): BelongsToMany {
        return $this->belongsToMany(static::class, 'stop_posts','stop_to_id','stop_from_id');
    }

    public function stopPostsTo(): BelongsToMany {
        return $this->belongsToMany(static::class, 'stop_posts','stop_from_id','stop_to_id');
    }

    public function stopNotificationsFrom(): BelongsToMany {
        return $this->belongsToMany(static::class, 'stop_notifications','stop_to_id','stop_from_id');
    }

    public function stopNotificationsTo(): BelongsToMany {
        return $this->belongsToMany(static::class, 'stop_notifications','stop_from_id','stop_to_id');
    }

    public function removeNotificationsFrom(): BelongsToMany {
        return $this->belongsToMany(static::class, 'user_removed_notifications','remover_id','user_notifications_removed_id');
    }

    public function removeNotificationsTo(): BelongsToMany {
        return $this->belongsToMany(static::class, 'user_removed_notifications','user_notifications_removed_id','remover_id');
    }
}
