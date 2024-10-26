<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\File;
use App\Http\Controllers\HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Support\Facades\Http;
class HelpController extends Controller
{
    protected $s3;
    public function __construct( )
    {
        $this->s3 = "";
        
    }
    public static function getYouTubeID($url)
    {
        $pattern = '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:watch\?v=|embed\/|v\/|.+\?v=)|youtu\.be\/)([^&\n?#]+)/';
        preg_match($pattern, $url, $matches);
        return $matches[1] ?? null;
    }

    public function uploadImageInContent($content)
    {
         
        $pattern = '/<img[^>]+src="([^"]+)"/';
        $modified_html = preg_replace_callback($pattern, function($matches) {
            // Perform upload action for each image
            $substring = $this->s3;
            
            if (strpos($matches[1], $substring) !== false) 
            {
                return $matches[0];
            }
            else
            {
                 
                $fileController = new \App\Http\Controllers\FilesController();
                $uploadedImagePath = $fileController->blogimageUpload($matches[1]);
               
                // Replace original src attribute with uploaded image link
                return str_replace($matches[1], $uploadedImagePath, $matches[0]);
            }
        
        }, $content);
        return  $modified_html;
    }
    public function removeImageStyle($content)
    {
        $modified_html = preg_replace_callback('/<img[^>]*>/', function($match) {
            return preg_replace('/\s*style\s*=\s*("[^"]*"|\'[^\']*\')/', '', $match[0]);
             
        }, $content);
        return  $modified_html ;
    }
}