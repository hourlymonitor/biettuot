<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentReview extends Model
{
    use HasFactory;

    protected $fillable = ['content_post_id', 'reviewer_user_id', 'status', 'notes'];

    public function contentPost(): BelongsTo
    {
        return $this->belongsTo(ContentPost::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_user_id');
    }
}
