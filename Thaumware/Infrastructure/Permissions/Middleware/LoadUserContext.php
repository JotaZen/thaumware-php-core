<?php


namespace Thaumware\Core\Permissions\Middleware;

use Thaumware\Core\Permissions\ScopedUser;
use Thaumware\Core\Permissions\UserContext;
use Illuminate\Support\Facades\Auth;
use Closure;

class LoadUserContext
{
    public function handle($request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');
        /** @var UserContext $userContext */
        $userContext = app(UserContext::class);

        $user = Auth::user();
        if ($user instanceof ScopedUser) {
            $userContext->loadScopedUser($user);
        }

        // Si necesitas cargar el token, puedes agregarlo aquí

        return $next($request);
    }
}