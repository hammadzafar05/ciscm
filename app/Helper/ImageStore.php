<?php

namespace App\Helper;

use Illuminate\Http\Request;
use File;
use Validator;

class ImageStore
{

    /**
     * @param Request $request
     * @param $folderName
     * @param $old_image
     * @return bool
     */
    public static function storeImage($image, $folderName, $old_image = NULL){
        $_CDN = Config('siteVars.siteVarsCdnFolderName');
        $_TEMP = Config('siteVars.siteVarsTempFolder');
        $_THUMBS = Config('siteVars.siteVarsTempFolderThumbs');

        $cdn = public_path($_CDN);
        $path = $cdn.$_TEMP;
        $path_thumbs = $path.$_THUMBS;

        $_directory = $cdn.$folderName.'/';
        if(!file_exists($_directory)) {
            File::makeDirectory($_directory, $mode = 0777, true, true);
        }

        $_directory_resize = $_directory.'resize/';
        if(!file_exists($_directory_resize)) {
            File::makeDirectory($_directory_resize, $mode = 0777, true, true);
        }

        $_directory_thumbs = $_directory.'thumbs/';
        if(!file_exists($_directory_thumbs)) {
            File::makeDirectory($_directory_thumbs, $mode = 0777, true, true);
        }

        if($image != ''){
            $old_path = $path.$image;
            $new_path = $_directory.$image;
            $move_main = File::move($old_path, $new_path);

            $old_path_resize = $path_thumbs.'resize_'.$image;
            $new_path_resize = $_directory_resize.'resize_'.$image;
            if(file_exists($old_path_resize)) {
                $move_resize = File::move($old_path_resize, $new_path_resize);
            }else{
                $move_resize = 1;
            }

            $old_path_thumbs = $path_thumbs.'small_'.$image;
            $new_path_thumbs = $_directory_thumbs.'small_'.$image;
            if(file_exists($old_path_thumbs)) {
                $move_thumbs = File::move($old_path_thumbs, $new_path_thumbs);
            }else{
                $move_thumbs = 1;
            }

            $old_path_thumbs = $path_thumbs.$image;
            $new_path_thumbs = $_directory_thumbs.'small_'.$image;
            if(file_exists($old_path_thumbs)) {
                $move_thumbs = File::move($old_path_thumbs, $new_path_thumbs);
            }else{
                $move_thumbs = 1;
            }

            if($move_main && $move_resize && $move_thumbs){
                if($old_image != '') {
                    $old_image_path = $_directory.$old_image;
                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }
                    $old_image_path_resize = $_directory_resize.'resize_'.$old_image;
                    if (file_exists($old_image_path_resize)) {
                        unlink($old_image_path_resize);
                    }
                    $old_image_path_thumbs = $_directory_thumbs.'small_'.$old_image;
                    if (file_exists($old_image_path_thumbs)) {
                        unlink($old_image_path_thumbs);
                    }
                }
            }
            return true;
        }
    }
}
