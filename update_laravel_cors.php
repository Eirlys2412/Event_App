<?php

// Script để cập nhật cấu hình CORS trong Laravel
// Chạy lệnh này bằng: php update_laravel_cors.php

// Thêm header cho phép tải file từ Flutter

// 1. Cập nhật .env nếu cần
$envPath = __DIR__ . '/.env';
if (file_exists($envPath)) {
    $env = file_get_contents($envPath);
    
    // Đảm bảo APP_URL đúng
    if (!preg_match('/APP_URL=http:\/\/[0-9.]+:8000/i', $env)) {
        $env = preg_replace('/APP_URL=.*/i', 'APP_URL=http://0.0.0.0:8000', $env);
        file_put_contents($envPath, $env);
        echo "Đã cập nhật APP_URL trong .env\n";
    }
}

// 2. Tạo file trong storage/app/public nếu thư mục uploads/resources không tồn tại
$resourcesDir = __DIR__ . '/storage/app/public/uploads/resources';
if (!is_dir($resourcesDir)) {
    mkdir($resourcesDir, 0755, true);
    echo "Đã tạo thư mục $resourcesDir\n";
    
    // Tạo file ảnh mặc định nếu cần
    $defaultImage = __DIR__ . '/public/storage/default-image.jpg';
    if (!file_exists($defaultImage) && is_dir(__DIR__ . '/public/storage')) {
        // Tạo hình ảnh mặc định đơn giản
        $img = imagecreatetruecolor(400, 300);
        $bg = imagecolorallocate($img, 200, 200, 200);
        $textcolor = imagecolorallocate($img, 0, 0, 0);
        
        imagefill($img, 0, 0, $bg);
        imagestring($img, 5, 150, 140, 'Default Image', $textcolor);
        
        imagejpeg($img, $defaultImage, 90);
        imagedestroy($img);
        
        echo "Đã tạo file ảnh mặc định tại $defaultImage\n";
    }
}

// 3. Tạo middleware để bắt và xử lý ngoại lệ khi tải hình ảnh
$corsMiddlewarePath = __DIR__ . '/app/Http/Middleware/ImageCorsMiddleware.php';
if (!file_exists($corsMiddlewarePath)) {
    $middleware = <<<'PHP'
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImageCorsMiddleware
{
    /**
     * Handle an incoming request.
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
PHP;
    
    file_put_contents($corsMiddlewarePath, $middleware);
    echo "Đã tạo middleware ImageCorsMiddleware\n";
    
    // Cập nhật Kernel.php để đăng ký middleware
    $kernelPath = __DIR__ . '/app/Http/Kernel.php';
    if (file_exists($kernelPath)) {
        $kernel = file_get_contents($kernelPath);
        
        // Kiểm tra nếu middleware chưa được đăng ký
        if (!strpos($kernel, 'ImageCorsMiddleware')) {
            // Tìm mảng middlewareGroups và thêm middleware vào nhóm web
            if (preg_match('/protected \$middlewareGroups = \[(.*?)\'web\' => \[(.*?)\](.*?)\]/s', $kernel, $matches)) {
                $webMiddleware = $matches[2];
                $updatedWebMiddleware = $webMiddleware . "            \\App\\Http\\Middleware\\ImageCorsMiddleware::class,\n";
                
                $kernel = str_replace($webMiddleware, $updatedWebMiddleware, $kernel);
                file_put_contents($kernelPath, $kernel);
                echo "Đã thêm ImageCorsMiddleware vào Kernel.php\n";
            }
        }
    }
}

// 4. Clear cache và restart server
echo "Cập nhật hoàn tất! Hãy chạy các lệnh sau để áp dụng thay đổi:\n";
echo "php artisan config:clear\n";
echo "php artisan route:clear\n";
echo "php artisan cache:clear\n";
echo "php artisan optimize:clear\n";
echo "php artisan serve --host=0.0.0.0\n";

echo "\nNếu ứng dụng Flutter vẫn gặp lỗi DioExceptionType.unknown, hãy thử:\n";
echo "1. Kiểm tra kết nối giữa máy ảo Flutter và Laravel server\n";
echo "2. Sử dụng mã trong tệp flutter_image_loading_fix_dio.dart\n";
echo "3. Kiểm tra path của hình ảnh đã chính xác chưa\n"; 