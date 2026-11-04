<?php

namespace App\Services\Content;

use App\Models\ContentPost;

class ContentDraftService
{
    public function createFromPrompt(string $prompt, int $userId): ContentPost
    {
        $title = trim($prompt);
        $body = "Mục tiêu: {$prompt}\n\nNội dung sẽ được viết ở đây...";

        return ContentPost::create([
            'user_id' => $userId,
            'title' => mb_substr($title, 0, 200) ?: 'Bài viết mới',
            'body' => $body,
            'status' => 'draft',
            'source_prompt' => $prompt,
        ]);
    }
}
