<?php

namespace Chernogolov\Fogcms\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Image;

class ImageController extends Controller
{
    //

    public static function uploadImage($image, $width = 1200, $height = 800)
    {
        $mimes = ['image/jpeg' => 'jpeg', 'image/png' => 'png', 'image/gif' => 'gif'];
        //check image url for other web site

        if(strpos($image, 'http://') === false && strpos($image, 'https://') === false)
        {
            if(!file_exists($image))
                $image = URL::to('/') . '/imagecache/original/'.$image;
        }

        if(is_object($image))
        {
            $translit_name = str_slug($image->getClientOriginalName());
            $filename  = time(). rand(0,10) . str_replace('.' . $image->getClientOriginalExtension(), '', $translit_name) . '.' . $image->getClientOriginalExtension();
            $img = Image::make($image->getRealPath());
        }
        else
        {
            $info = pathinfo($image);
            $flag = true;
            $try = 1;
            while ($flag && $try <= 3):
                try {

                    $img = Image::make($image);
                    $ext = $mimes[$img->mime];
                    $filename  = time() . rand(0,10) . str_slug($info['filename']) . '.' . $ext;
                }
                catch (\Exception $e) {
                }
                $try++;
            endwhile;
        }

        if(isset($img))
        {
            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            Storage::put('/public/' . date('Y-m') . '/' . $filename,  (string) $img->encode());

            return (date('Y-m') . '/' . $filename);
        }
        else
            return false;


    }

    public function ajaxUpload(Request  $request)
    {
        $uploads = [];
        $post_data = $request->all();
        if(!empty($post_data))
            foreach($_FILES as $file)
               $uploads[] = Self::uploadImage($file['tmp_name']);

        return json_encode($uploads);
    }

    public function ajaxDelete(Request  $request)
    {
        $post_data = $request->all();
        if(isset($post_data['filename']))
        {
            $r = Storage::delete('/public/' . $post_data['filename']);
            if($r)
                return 'Успех';
        }
    }

}
