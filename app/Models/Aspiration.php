<?php

namespace App\Models;

use App\Enums\AspirationStatus;
use Database\Factories\AspirationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'description', 'user_id', 'status'])]
class Aspiration extends Model
{
    /** @use HasFactory<AspirationFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => AspirationStatus::class,
        ];
    }

    /**
     * Get the user who authored this aspiration.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all categories attached to this aspiration.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'aspiration_category');
    }

    /**
     * Get all upvotes for this aspiration.
     */
    public function upvotes(): HasMany
    {
        return $this->hasMany(Upvote::class);
    }

    /**
     * Get all comments for this aspiration.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
