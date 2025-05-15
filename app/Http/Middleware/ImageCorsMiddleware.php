<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImageCorsMiddleware
{
    /**
     * Handle an incoming request.
     * 'paths' => ['api/*', 'storage/*'],

     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra nếu yêu cầu là tải file hình ảnh
        if ($request->is('storage/*') && $this->isImageRequest($request->path())) {
            $response = $next($request);
            
            // Thêm header CORS
            $response->header('Access-Control-Allow-Origin', '*');
            $response->header('Access-Control-Allow-Methods', 'GET, HEAD, OPTIONS');
            $response->header('Access-Control-Allow-Headers', '*');
            $response->header('Access-Control-Max-Age', '86400');
            $response->header('Cache-Control', 'max-age=86400, public');
            $response->header('Connection', 'Keep-Alive');
            $response->header('Keep-Alive', 'timeout=30, max=200');
            
            return $response;
        }
        
        return $next($request);
    }
    
    /**
     * Kiểm tra xem yêu cầu có phải là để tải file hình ảnh không
     */
    private function isImageRequest($path)
    {
        
        $extensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        return in_array(strtolower($extension), $extensions);
    }
}
