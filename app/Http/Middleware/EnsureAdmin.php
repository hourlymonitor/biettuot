<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $adminEmails = collect(config('admin.emails', []))
            ->map(static fn (string $email): string => mb_strtolower(trim($email)))
            ->filter();

        abort_unless(
            $request->user() && $adminEmails->contains(mb_strtolower($request->user()->email)),
            403
        );

        return $next($request);
    }
}
