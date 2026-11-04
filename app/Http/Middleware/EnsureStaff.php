<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStaff
{
    public function handle(Request $request, Closure $next): Response
    {
        $email = mb_strtolower((string) $request->user()?->email);
        $staffEmails = collect(array_merge(
            config('admin.emails', []),
            config('admin.moderator_emails', []),
        ))->map(static fn (string $value): string => mb_strtolower(trim($value)))->filter();

        abort_unless($email !== '' && $staffEmails->contains($email), 403);

        return $next($request);
    }
}
