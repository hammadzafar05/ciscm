<?php

namespace App\Http\Controllers;

use App\Helper\AppHelper;
use App\Helper\ImageStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use File;
use Validator;
use Intervention\Image\ImageManagerStatic as Image;


class SettingsController extends Controller
{

    public $current_module_link, $page_title, $image_cdn;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->current_module_link = 'settings';

        $page_title = 'Settings';
        $this->current_module_link = 'settings';
        $this->page_title = $page_title;
        $this->image_cdn = 'settings/';

        $this->middleware('auth');
    }

    /**
     * Show the Settings Form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {

    }


    /**
     * Upload Image
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function uploadImageToServer(Request $request){
        $_CDN = 'cdn/';
        $_TEMP = 'temp/';
        $_THUMBS = 'thumbs/';

        $cdn = public_path($_CDN);
        if(!file_exists($cdn)) {
            File::makeDirectory($cdn, $mode = 0777, true, true);
        }

        $path = $cdn.$_TEMP;
        if(!file_exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        $path_thumbs = $path.$_THUMBS;
        if(!file_exists($path_thumbs)) {
            File::makeDirectory($path_thumbs, $mode = 0777, true, true);
        }

        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        if ($validator->passes()) {

            $image_width = $request->input('image_width');
            $image_height = $request->input('image_height');
            $thumbs_width = $request->input('thumbs_width');
            $thumbs_height = $request->input('thumbs_height');

            $image = $request->file('image');
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move($path, $new_name);

            /*
            $image_resize = Image::make($path.$new_name);
            $image_resize->resize(500, 500,function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image_resize->save($path_thumbs.'small' .$new_name);
            */

            $image_resize = Image::make($path.$new_name);
            $image_resize->fit($image_width, $image_height,function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image_resize->save($path_thumbs.'resize_'.$new_name);

            $image_resize = Image::make($path.$new_name);
            $image_resize->fit($thumbs_width, $thumbs_height,function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image_resize->save($path_thumbs.'small_' .$new_name);

            $image_path =  asset( $_CDN.$_TEMP.$_THUMBS.'small_' .$new_name);

            $data['path'] = $image_path;
            $data['name'] = $new_name;
            return AppHelper::RespondWithSuccess(
                'Image Upload Successfully',
                $data
            );

        } else {
            return AppHelper::RespondWithError(
                $validator->errors()->first()
            );
        }
    }
}
