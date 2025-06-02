<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CaptureReferral
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->has('code')) {
            return $next($request);
        }

        $response = $next($request);
        $referralCode = $request->query('code');

        $cookie = null;
        if (!$request->hasCookie(get_referral_key())) {
            $cookie = cookie(get_referral_key(), $referralCode, 7 * 24 * 60);
        }

        /** @var User $user */
        $user = User::query()->where('referral_code', $referralCode)->first();
        if (!$user) {
            return $cookie ? $response->withCookie($cookie) : $response;
        }

        $cacheKey = "referral_tracked_{$referralCode}_{$request->ip()}";
        if (! Cache::has($cacheKey)) {
            $user->increment('referral_link_view_count');
            Cache::put($cacheKey, true, now()->addDay());
        }

        return $cookie ? $response->withCookie($cookie) : $response;
    }
}
