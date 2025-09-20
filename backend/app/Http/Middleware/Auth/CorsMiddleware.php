<?php

namespace App\Http\Middleware\Auth;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // معالجة طلبات OPTIONS (preflight)
        if ($request->isMethod('OPTIONS')) {
            return $this->handlePreflightRequest();
        }

        // معالجة الطلبات العادية
        $response = $next($request);

        // إضافة headers لـ CORS
        return $this->addCorsHeaders($response);
    }

    /**
     * Handle preflight OPTIONS requests.
     */
    protected function handlePreflightRequest(): Response
    {
        return response('', 200)
            ->header('Access-Control-Allow-Origin', $this->getAllowedOrigins())
            ->header('Access-Control-Allow-Methods', $this->getAllowedMethods())
            ->header('Access-Control-Allow-Headers', $this->getAllowedHeaders())
            ->header('Access-Control-Allow-Credentials', 'true')
            ->header('Access-Control-Max-Age', '86400'); // 24 ساعة
    }

    /**
     * Add CORS headers to the response.
     */
    protected function addCorsHeaders(Response $response): Response
    {
        return $response
            ->header('Access-Control-Allow-Origin', $this->getAllowedOrigins())
            ->header('Access-Control-Allow-Methods', $this->getAllowedMethods())
            ->header('Access-Control-Allow-Headers', $this->getAllowedHeaders())
            ->header('Access-Control-Allow-Credentials', 'true')
            ->header('Access-Control-Expose-Headers', 'Authorization, X-CSRF-TOKEN');
    }

    /**
     * Get allowed origins from config.
     */
    protected function getAllowedOrigins(): string
    {
        $allowedOrigins = config('cors.allowed_origins', []);
        
        if (empty($allowedOrigins)) {
            return '*';
        }

        $currentOrigin = request()->header('Origin');
        
        if (in_array($currentOrigin, $allowedOrigins)) {
            return $currentOrigin;
        }

        return implode(', ', $allowedOrigins);
    }

    /**
     * Get allowed methods from config.
     */
    protected function getAllowedMethods(): string
    {
        $methods = config('cors.allowed_methods', ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS']);
        return implode(', ', $methods);
    }

    /**
     * Get allowed headers from config.
     */
    protected function getAllowedHeaders(): string
    {
        $headers = config('cors.allowed_headers', [
            'Content-Type',
            'Authorization',
            'X-Requested-With',
            'X-CSRF-TOKEN',
            'X-XSRF-TOKEN',
            'Accept',
            'Origin'
        ]);
        
        return implode(', ', $headers);
    }
}