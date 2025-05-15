<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\File;
use Intervention\Image\Facades\Image;

class FilesController extends Controller
{
    //
    public function ckeditorUpload(Request $request)
    {
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the validation rules as needed
        ]);
    
        if ($request->hasFile('upload')) {

            $filename_ten = $request->file('upload')->getClientOriginalName();
            $ext = '.'.$request->file('upload')->getClientOriginalExtension();
            $filename =  str_replace(  $ext , '',$filename_ten);
            $filename = $filename . '_' .Str::random(5) .   $ext;
            $awsKey = env('AWS_ACCESS_KEY_ID');
            $awsSecret = env('AWS_SECRET_ACCESS_KEY');
            if ($awsKey && $awsSecret) {
                // Store the file on S3
                $disk = 's3';
                $folder='ckupload';
            } else {
                // Store the file locally
                $disk = 'local';
                $folder='public/ckupload';
            }
            $file = $request->file('upload');
            $storagePath = $file->storeAs(
                $folder,
                $filename,
                $disk
            );
            
            if($disk == 's3') {
                // Đối với S3, tạo URL từ cấu hình bucket và region
                $s3Url = env('AWS_URL', 'https://s3.' . env('AWS_DEFAULT_REGION', 'us-east-1') . '.amazonaws.com');
                $bucket = env('AWS_BUCKET', '');
                $url = $s3Url . '/' . $bucket . '/' . $storagePath;
            } else {
                // Đối với local storage
                $relativePath = str_replace('public/', '', $storagePath);
                $url = asset('storage/' . $relativePath);
            }
            
            return response()->json(['fileName' => $filename_ten, 'uploaded'=> 1, 'url' => $url]);
        }
        return response()->json(['error' => 'No file uploaded.']);
    }

    public function avartarUpload(Request $request)
    {
        $filename = $request->file('file')->getClientOriginalName();
        $ext = '.'.$request->file('file')->getClientOriginalExtension();
       
        $filename =  str_replace(  $ext , '',$filename);
        // echo $filename;
        $link = $request->hasFile('file') ? $this->store($request->file('file'), 'avatar',$filename) : null;
       
        return response()->json(['status'=>'true','link'=>$link]);
    }
    public function productUpload(Request $request)
    {
        
        $filename = $request->file('file')->getClientOriginalName();
        $ext = '.'.$request->file('file')->getClientOriginalExtension();
        $filename =  str_replace(  $ext , '',$filename);
       
        $link = $request->hasFile('file') ? $this->store($request->file('file'), 'products', $filename) : null;
        
        return response()->json(['status'=>'true','link'=>$link]);
    }
    public function blogimageUpload($data)
{
    if (!$data) return null;

    // Nếu là ảnh base64 (gửi từ Flutter)
    if (Str::startsWith($data, 'data:image')) {
        try {
            $folder = 'avatar'; // hoặc 'blogs'
            $image = preg_replace('#^data:image/\w+;base64,#i', '', $data);
            $image = str_replace(' ', '+', $image);
            $imageData = base64_decode($image);

            $fileName = 'blog_' . time() . '.jpg';
            $filePath = storage_path("app/public/{$folder}/" . $fileName);
            file_put_contents($filePath, $imageData);

            return asset("storage/{$folder}/" . $fileName);
        } catch (\Exception $e) {
            return null;
        }
    }

    // Nếu là URL hoặc local path
    if (!filter_var($data, FILTER_VALIDATE_URL)) {
        $data = public_path('storage/' . ltrim($data, '/'));
    }

    try {
        $imageContent = file_get_contents($data);
        $tempImagePath = tempnam(sys_get_temp_dir(), 'image');
        file_put_contents($tempImagePath, $imageContent);

        $imageInfo = @getimagesize($tempImagePath);
        if (!$imageInfo) {
            unlink($tempImagePath);
            return null;
        }

        if (filesize($tempImagePath) > 0.5 * 1024 * 1024) {
            $this->compressImage($tempImagePath, $imageInfo['mime']);
        }

        $folder = 'blogs';
        $disk = env('AWS_ACCESS_KEY_ID') && env('AWS_SECRET_ACCESS_KEY') ? 's3' : 'local';
        $folder = $disk == 's3' ? 'blogs' : 'public/blogs';

        $stored = Storage::disk($disk)->putFile($folder, new File($tempImagePath));

        if ($disk === 's3') {
            // Tạo URL cho S3
            $s3Url = env('AWS_URL', 'https://s3.' . env('AWS_DEFAULT_REGION', 'us-east-1') . '.amazonaws.com');
            $bucket = env('AWS_BUCKET', '');
            $url = $s3Url . '/' . $bucket . '/' . $stored;
        } else {
            $relativePath = str_replace('public/', '', $stored);
            $url = asset('storage/' . $relativePath);
        
            // ⚠️ Fix: thêm domain nếu thiếu
            if (!Str::startsWith($url, 'http')) {
                $url = url($url);  // ← thêm domain đầy đủ như http://localhost:8000/...
            }
        }
        

        unlink($tempImagePath);
        return $url;
    } catch (\Exception $e) {
        return null;
    }
}

    private function compressImage($imagePath, $mimeType)
    {
        // Load the image based on the MIME type
        switch ($mimeType) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($imagePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($imagePath);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($imagePath);
                break;
            default:
                // Unsupported image format
                return;
        }
        // Compress the image and overwrite the original file
        imagejpeg($image, $imagePath, 70); // Adjust compression quality as needed
        // Free up memory
        imagedestroy($image);
    }

    public function FileUpload(Request $request)
    {
        $filename = $request->file('file')->getClientOriginalName();
        $ext = '.'.$request->file('file')->getClientOriginalExtension();
        $filename =  str_replace(  $ext , '',$filename);

        $link = $request->hasFile('file') ? $this->store($request->file('file'), 'Categories',$filename) : null;
        
        return response()->json(['success'=>$link]);
    }
    public function store(UploadedFile $file, $folder = null, $filename = null)
    {
        $awsKey = env('AWS_ACCESS_KEY_ID');
        $awsSecret = env('AWS_SECRET_ACCESS_KEY');
        if ($awsKey && $awsSecret) {
            // Store the file on S3
            $disk = 's3';
        } else {
            // Store the file locally
            $disk = 'local';
            $folder = 'public/'.$folder;
        }
        $name = !is_null($filename) ? $filename.'_'.Str::random(5) : Str::random(25);
        $storagePath = $file->storeAs(
            $folder,
            $name . "." . $file->getClientOriginalExtension(),
            $disk
        );
        
        if($disk == 's3') {
            // Tạo URL cho S3
            $s3Url = env('AWS_URL', 'https://s3.' . env('AWS_DEFAULT_REGION', 'us-east-1') . '.amazonaws.com');
            $bucket = env('AWS_BUCKET', '');
            $link = $s3Url . '/' . $bucket . '/' . $storagePath;
        } else {
            // Đối với local storage
            $relativePath = str_replace('public/', '', $storagePath);
            $link = asset('storage/' . $relativePath);
        }
        
        return $link;
    }
  
}
