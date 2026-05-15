<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UseTabScopedAccount
{
    private const SESSION_KEY = 'clinicaly_role_sessions';

    public function handle(Request $request, Closure $next): Response
    {
        $role = $this->roleFromRequest($request);

        if ($role) {
            $user = $this->userForRole($role);

            if ($user) {
                Auth::shouldUse('web');
                Auth::guard('web')->setUser($user);
                $request->setUserResolver(fn () => $user);
            }
        }

        $response = $next($request);

        if ($role && $response->isRedirection()) {
            $this->preserveRoleOnRedirect($response, $role);
        }

        return $response;
    }

    private function roleFromRequest(Request $request): ?string
    {
        $role = $request->query('as_role')
            ?? $request->input('as_role')
            ?? $request->headers->get('X-Clinicaly-Role');

        $role = mb_strtolower(trim((string) $role));

        return in_array($role, [User::ROLE_CLINIC, User::ROLE_DOCTOR, User::ROLE_PATIENT], true)
            ? $role
            : null;
    }

    private function userForRole(string $role): ?User
    {
        $slots = session(self::SESSION_KEY, []);
        $userId = $slots[$role] ?? null;

        if (! $userId) {
            return null;
        }

        $user = User::find($userId);

        if (! $user) {
            return null;
        }

        return match ($role) {
            User::ROLE_CLINIC => $user->isClinic() ? $user : null,
            User::ROLE_DOCTOR => $user->isDoctor() ? $user : null,
            User::ROLE_PATIENT => $user->isPatient() ? $user : null,
            default => null,
        };
    }

    private function preserveRoleOnRedirect(Response $response, string $role): void
    {
        $location = $response->headers->get('Location');

        if (! $location || str_contains($location, 'as_role=')) {
            return;
        }

        $parts = parse_url($location);
        $query = $parts['query'] ?? '';
        parse_str($query, $params);
        $params['as_role'] = $role;

        $rebuilt = '';

        if (isset($parts['scheme'], $parts['host'])) {
            $rebuilt .= $parts['scheme'].'://'.$parts['host'];
            $rebuilt .= isset($parts['port']) ? ':'.$parts['port'] : '';
        }

        $rebuilt .= $parts['path'] ?? '';
        $rebuilt .= '?'.http_build_query($params);
        $rebuilt .= isset($parts['fragment']) ? '#'.$parts['fragment'] : '';

        $response->headers->set('Location', $rebuilt);
    }
}
