<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $response = $next($request);
        
        $isApiRequest = $request->expectsJson() || $request->is('api/*');
        
        if ($isApiRequest && $request->user()) {
            $userRole = $request->user()->role;
            $redirectPath = $userRole === 'recruiter' 
                ? '/recruiter/dashboard' 
                : '/candidate/dashboard';
                
            if ($response instanceof JsonResponse) {
                $data = $response->getData(true);
                $data['redirect'] = $redirectPath;
                $response->setData($data);
            }
        }
        
        return $response;
    }
}