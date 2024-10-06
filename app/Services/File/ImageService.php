<?php


namespace App\Services\File;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class ImageService
{

    /**
     * @param $path
     */

    protected  function create_new_directory($path){
        if (!File::exists($path)) {
             File::makeDirectory($path, 0777, true);
        }
        return ;
    }

    /** Check available image format
     * @param $image
     * @return string|string[]
     */
    protected function checkAvaibleFormat($image){
        try {
            $mime = Image::make($image)->mime();// если нет mime TYPE  вернет ошибку
            if ($mime === 'image/svg')
                $extension = '.svg';
            else if ($mime === 'image/jpeg')
                $extension = '.jpg';
            else if ($mime === 'image/png')
                $extension = '.png';
            else if ($mime === 'image/gif')
                $extension = '.gif';
            else if($mime === 'image/tiff'){
                $extension = '.tiff';
            }else {
                return  ['msg' => "Undefined file format : need be {jpeg / png} " , "code" => 500];
            }
        } catch(\Exception $e){
            return $e->getMessage();
        }
        return $extension;
    }

    /** Method to just store image
     * @param $image
     * @param $path
     * @return string
     */

    public function store_image( $image  , $path){
        $extension =  $this->checkAvaibleFormat($image);
        $this->create_new_directory(public_path() .$path);
        $org_path = $path ."/".  rand(1111, 9999) . time() . $extension;
        if(!file_exists(public_path() .$org_path)) {
            Image::make($image)->save(public_path() . $org_path);
        }
        return $org_path;
    }

}
