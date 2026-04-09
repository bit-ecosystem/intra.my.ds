<?php

declare(strict_types=1);

namespace Bites\Organization\Identity\Http\Controllers\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class EnsureApiTokenScopes
{
    /**
     * Ensure the authenticated token has the required scopes.
     */
    public function handle(Request $request, Closure $next, ...$scopes)
    {
        $user = $request->user();

        if (! $user || ! $request->user()->token()) {
            throw new AccessDeniedHttpException('Missing access token.');
        }

        foreach ($scopes as $scope) {
            if (! $request->user()->tokenCan($scope)) {
                throw new AccessDeniedHttpException('Missing required scope: '.$scope);
            }
        }

        return $next($request);
    }
}
