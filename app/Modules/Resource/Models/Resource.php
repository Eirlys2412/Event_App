<?php

namespace App\Modules\Resource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Http\Controllers\FilesController;
use Illuminate\Support\Facades\Storage;

class Resource extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'file_name',
        'file_type',
        'file_size',
        'url',
        'type_code',
        'link_code',
        'code'
    ];

    //create
    public static function createResource($request, $file = null, $moduleName = null)
    {
        $title = $request->title ?? 'Resource Default Title';
        if ($file) {
            $existingResource = Resource::where('file_name', $file->getClientOriginalName())
                ->where('file_size', $file->getSize())
                ->where('code', $moduleName)
                ->first();
    
            if ($existingResource) {
                return $existingResource; // Trả về resource đã tồn tại
            }
        }
        $data = [
            'title' => $title,
            'code' => $moduleName,
            'slug' => self::generateSlug($title),
        ];

        if (isset($request->type_code)) {
            $data['type_code'] = $request->type_code;
        } else {
            $resourceType = self::determineResourceType($file);
            $data['type_code'] = $resourceType;
        }

        if (isset($request->link_code)) {
            $data['link_code'] = $request->link_code;
        } else {
            $linkTypes = self::generateLinkCode($file);
            $data['link_code'] = $linkTypes;
        }

        if ($file) {
            $filesController = new FilesController();
            $folder = 'uploads/resources';
            $url = $filesController->store($file, $folder);

            $data['file_name'] = $file->getClientOriginalName();
            
            // Tạo file_type dựa trên phần mở rộng của file gốc
            $extension = strtolower($file->getClientOriginalExtension());
            $fileType = self::getMimeTypeFromExtension($extension);
            $data['file_type'] = $fileType;
            
            $data['file_size'] = $file->getSize();
            $data['url'] = $url;
        }

        if (isset($request->url) && !$file) {
            $youtubeID = self::getYouTubeID($request->url);
            if ($youtubeID) {
                $data['url'] = "https://www.youtube.com/watch?v=" . $youtubeID;
            } else {
                $data['url'] = $request->url;
            }
        }

        return self::create($data);
    }

    //Update
    public function updateResource($request, $file = null)
    {
        // Thiết lập dữ liệu cần cập nhật
        $title = $request['title'] ?? $this->title;
        $data = [
            'title' => $title,
            'slug' => self::generateSlug($title, $this),
        ];

        // Kiểm tra và cập nhật type_code nếu có trong request
        if (isset($request['type_code'])) {
            $data['type_code'] = $request['type_code'];
        }

        // Kiểm tra và cập nhật link_code nếu có trong request
        if (isset($request['link_code'])) {
            $data['link_code'] = $request['link_code'];
        }

        // Nếu có file mới, xử lý file
        if ($file) {
            // Xóa file cũ nếu có
            $this->deleteFile();

            // Lưu file mới
            $filesController = new FilesController();
            $folder = 'uploads/resources';
            $url = $filesController->store($file, $folder);

            // Cập nhật dữ liệu file vào resource
            $data['file_name'] = $file->getClientOriginalName();
            
            // Sử dụng phần mở rộng gốc để xác định file_type
            $extension = strtolower($file->getClientOriginalExtension());
            $fileType = self::getMimeTypeFromExtension($extension);
            $data['file_type'] = $fileType;
            
            $data['file_size'] = $file->getSize();
            $data['url'] = $url;
        }

        // Nếu có URL mới (YouTube hoặc link khác), xử lý URL
        if (isset($request['url'])) {
            $youtubeID = self::getYouTubeID($request['url']);
            if ($youtubeID) {
                $data['url'] = "https://www.youtube.com/watch?v=" . $youtubeID;
            } else {
                $data['url'] = $request['url'];
            }
        }

        // Cập nhật dữ liệu trong resource
        $this->fill($data);
        $this->save();

        return $this;
    }

    /**
     * Tạo MIME type từ phần mở rộng của file
     *
     * @param string $extension
     * @return string
     */
    public static function getMimeTypeFromExtension($extension)
    {
        $mimeTypes = [
            'jpg' => 'image/jpg',
            'JPG' => 'image/jpg',
            'jpeg' => 'image/jpeg',
            'JPEG' => 'image/jpeg',
            'png' => 'image/png',
            'PNG' => 'image/png',
            'gif' => 'image/gif',
            'GIF' => 'image/gif',
            'bmp' => 'image/bmp',
            'BMP' => 'image/bmp',
            'svg' => 'image/svg+xml',
            'SVG' => 'image/svg+xml',
            'webp' => 'image/webp',
            'WEBP' => 'image/webp',
            'mp4' => 'video/mp4',
            'MP4' => 'video/mp4',
            'avi' => 'video/avi',
            'AVI' => 'video/avi',
            'mov' => 'video/quicktime',
            'MOV' => 'video/quicktime',
            'wmv' => 'video/x-ms-wmv',
            'WMV' => 'video/x-ms-wmv',
            'mp3' => 'audio/mpeg',
            'MP3' => 'audio/mpeg',
            'wav' => 'audio/wav',
            'WAV' => 'audio/wav',
            'ogg' => 'audio/ogg',
            'OGG' => 'audio/ogg',
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed'
        ];
        
        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }


    //Xóa resource và file của nó.
    public function deleteResource()
    {
        $this->deleteFile();
        return $this->delete();
    }

    //xóa file liên kết với resource
    private function deleteFile()
    {
        if (empty($this->path)) {
            Log::warning("Path is empty, no file to delete.");
            return;
        }

        Log::info("Attempting to delete file at path: {$this->path}");

        $this->deleteFromDisk('public');
        $this->deleteFromDisk('s3');
    }

    //Xóa file từ disk
    private function deleteFromDisk($disk)
    {
        if (Storage::disk($disk)->exists($this->path)) {
            Storage::disk($disk)->delete($this->path);
            Log::info("File deleted from {$disk} disk: {$this->path}");
        }
    }

    // Tạo slug
    public static function generateSlug($title, $model = null, $existingSlugs = [])
    {

        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        // Lấy danh sách slug trùng lặp từ cơ sở dữ liệu
        $existingSlugsFromDb = Resource::pluck('slug')->toArray();

        // Kết hợp danh sách đã có và từ cơ sở dữ liệu
        $existingSlugs = array_merge($existingSlugs, $existingSlugsFromDb);

        while (in_array($slug, $existingSlugs)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }



    // Lấy ID YouTube từ URL.
    public static function getYouTubeID($url)
    {
        $pattern = '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:watch\?v=|embed\/|v\/|.+\?v=)|youtu\.be\/)([^&\n?#]+)/';
        preg_match($pattern, $url, $matches);
        return $matches[1] ?? null;
    }

    // Xác định loại resource từ mimeType của file
    public static function determineResourceType($file)
    {
        if ($file instanceof \Illuminate\Http\UploadedFile) {
            $mimeType = $file->getMimeType();
            if (str_starts_with($mimeType, 'image/')) return 'Image';
            if (str_starts_with($mimeType, 'video/')) return 'Video';
            if (str_starts_with($mimeType, 'audio/')) return 'Audio';
            return 'Document';
        }
        return 'Document';
    }
    //   Sinh mã link_code từ URL.
    public static function generateLinkCode($url)
    {
        $linkType = ResourceLinkType::where('viewcode', 'LIKE', "%$url%")->first();

        if ($linkType) {
            return $linkType->code;
        }
        return null;
    }
}
