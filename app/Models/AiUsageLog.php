<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiUsageLog extends Model
{
    protected $fillable = [
        'user_id',
        'chat_session_id',
        'provider',
        'model',
        'status',
        'duration_ms',
        'prompt_tokens',
        'completion_tokens',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
