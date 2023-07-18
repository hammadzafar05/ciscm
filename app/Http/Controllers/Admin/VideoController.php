<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\BaseForm;
use App\Lib\HelperTrait;
use App\V2\Model\VideoTable;
use App\Video;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Laminas\Form\Element\File;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\InputFilter\Input;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\File\Extension;
use Laminas\Validator\File\Size;


class VideoController extends Controller
{
    use HelperTrait;
    private $data = [];

    private $videoDir = 'uservideo';
    private $videoPath = 'uservideo';
    //create video list
    public function index(Request $request) {
        // TODO Auto-generated NewssController::index(Request $request) default action
        $table = new VideoTable();

        $filter = request()->get('filter');


        if (empty($filter)) {
            $filter=null;
        }



        $sort = request()->get('sort', null);
        if (empty($sort)) {
            $sort=null;
        }


        $text = new Text('filter');
        $text->setAttribute('class','form-control');
        $text->setAttribute('placeholder',__lang('filter-video-name'));
        $text->setValue($filter);


        $sortSelect = new Select('sort');
        $sortSelect->setAttribute('class','form-control');
        $sortSelect->setValueOptions([
            'recent'=>__lang('recently-added'),
            'asc'=>__lang('alphabetical-(ascending)'),
            'desc'=>__lang('alphabetical-(descending)'),
        ]);
        $sortSelect->setEmptyOption('--'.__lang('sort').'--');
        $sortSelect->setValue($sort);




        $paginator = $table->getVideos(true,$filter,$sort);

        $paginator->setCurrentPageNumber((int)request()->get('page', 1));
        $paginator->setItemCountPerPage(30);
        return viewModel('admin',__CLASS__,__FUNCTION__,array(
            'paginator'=>$paginator,
            'pageTitle'=>__lang('video-library'),
            'filter'=>$filter,
            'text'=>$text,
            'sortSelect'=>$sortSelect,
            'sort'=>$sort
        ));


    }


    public function add(Request $request){



        if(request()->isMethod('post')){
            $form = $this->getUploadForm();
            $formData = request()->all();

            $form->setData($_FILES);
            $file =  $_FILES['files'];

            if(!$form->isValid()){

                if (is_array($file) && isset($file['name'][0]) && isset($file['size'][0])){
                    $json = json_encode([
                        'files'=>[
                            [
                                'name'=>$file['name'][0],
                                'size'=>$file['size'][0],
                                'error'=>__lang('invalid-upload')
                            ]
                        ]
                    ]);
                }
                else{
                    $json = json_encode([
                        'files'=>[
                            [
                                'name'=>'',
                                'size'=>'',
                                'error'=>__lang('invalid-upload')
                            ]
                        ]
                    ]);
                }


                exit($json);
            }



            $targetDir = $this->videoPath;
            if (!is_dir($targetDir)){

                mkdir($targetDir);
                touch($this->videoPath.'/.htaccess');
                if (file_exists('usermedia/.htaccess')){
                    file_put_contents($this->videoPath.'/.htaccess',file_get_contents('usermedia/.htaccess'));
                }

            }

            //get file name
            $name = $file['name'];
            $tmpName = $file['tmp_name'];

            //get extension
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $uniqueID = safeUrl(uniqid());
            $newVideoName= $uniqueID.'.'.$ext;

            //create directory for file
            $video = new Video();
            $video->name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $name);
            $video->admin_id = $this->getAdminId();
            $video->created_at = time();
            $video->file_name = $newVideoName;
            $video->file_size = $file['size'];
            $video->length = 0;
            $video->save();

            $videoDirectory = $targetDir.'/'.$video->id;

            mkdir($videoDirectory);


            //create video information file and put in folder


            $videoDestination= $videoDirectory.'/'.$newVideoName;
            rename($tmpName,$videoDestination);

            //now do image generation
            if (!is_dir($this->videoPath)){
                mkdir($this->videoPath);
            }

            try {

                //get file extension
                $fileExtension = strtolower(pathinfo($videoDestination)['extension']);
                if($fileExtension != 'mp3')
                {

                    $ffmpegExec = env('FF_MPEG');
                    $ffprobeExec = env('FF_PROBE');
                    if (!empty($ffmpegExec) && !empty($ffprobeExec)){
                        $config = array(
                            'ffmpeg.binaries'  => $ffmpegExec,
                            'ffprobe.binaries' => $ffprobeExec,
                            'timeout'          => 3600, // The timeout for the underlying process
                            'ffmpeg.threads'   => 12,   // The number of threads that FFMpeg should use
                        );
                        $ffmpeg = \FFMpeg\FFMpeg::create($config);
                        $ffprobe = \FFMpeg\FFProbe::create($config);

                    }
                    else{
                        $ffmpeg = \FFMpeg\FFMpeg::create();
                        $ffprobe = \FFMpeg\FFProbe::create();
                    }


                    $videoObj = $ffmpeg->open($videoDestination);
                    $videoObj->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(3))
                        ->save("{$videoDirectory}/{$uniqueID}.jpg");

                    $duration = $ffprobe->streams($videoDestination)
                        ->videos()
                        ->first()
                        ->get('duration');

                    $video->length = gmdate("H:i:s", $duration);
                    $video->save();
                }


            }catch (\Exception $exception){

            }



            //get length of video
            if(!isset($duration)){
                try{
                    $getID3 = new \getID3();
                    $getFile = $getID3->analyze($videoDestination);

                    //  $video->length = gmdate("H:i:s", $duration);
                    $video->length = gmdate("H:i:s", $getFile['playtime_string']);
                    $video->save();
                }
                catch (\Exception $ex){

                }
            }


            /*       $videoObj = $ffmpeg->open($videoDestination);
                   $videoObj->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(3))
                       ->save("{$dir}/{$video->id}.jpg");*/

            $json = json_encode([
                'files'=>[
                    [
                        'name'=>__lang('file-upload-success-msg').': '.$file['name'],
                        'size'=>$file['size'],
                        'thumbnailUrl'=>$this->getBaseUrl().'/img/success.png'
                    ]
                ]
            ]);


            exit($json);

        }

        $maxSize = $this->setting('general_video_max_size',200).'MB';
        $maxSizeB = $this->setting('general_video_max_size',200) * 1048576 ;
        return view('admin.video.add',['pageTitle'=>__lang('Add Videos'),'maxSize'=>$maxSize,'maxSizeB'=>$maxSizeB]);
    }

    private function getUploadForm(){

        $form = new BaseForm();

        $file = new File('files');
        $file->setLabel('Your File')
            ->setAttribute('id','file_path')
            ->setAttribute('required','required');
        $form->add($file);
        $form->setInputFilter($this->getUploadFilter());
        return $form;

    }

    private function getUploadFilter(){

        $filter = new InputFilter();

        $maxSize = $this->setting('general_video_max_size',200) * 1048576 ;

        $input = new Input('files');
        $input->setRequired(true);
        $input->getValidatorChain()
            ->attach(new Size($maxSize))
            ->attach(new Extension('mp4,ogg,webm,mp3'));

        $filter->add($input);



        return $filter;
    }


    public function delete(Request $request,$id)
    {
        $table = new VideoTable();
        try{
            //remove thumbnail
            $video = Video::find($id);
            $dir = $this->videoPath.'/'.$video->id;
            rrmdir($dir);
            //@unlink($file);

            $table->deleteRecord($id);
            flashMessage(__lang('Record deleted'));
        }
        catch(\Exception $ex){
            $this->deleteError();
        }

        return back();
    }

    public function removeimage(Request $request,$id){
        $video = Video::find($id);
        $this->validateAdminOwner($video);
        $path = $this->videoPath.'/'.$video->id.'/'.videoImage($video->file_name);
        unlink($path);
        return back();
    }


    public function play(Request $request,$id){

        $video = Video::find($id);
        $this->data['video'] = $video;

        // $this->data['videoUrl'] = $this->getBaseUrl().'/uservideo/'.$video->id.'/'.$video->file_name;
        $this->data['videoUrl'] = adminUrl(['controller'=>'video','action'=>'serve','id'=>$video->id]);
        $poster = '';
        $name = fileName($video->file_name);
        if (file_exists('uservideo/'.$video->id."/{$name}.jpg")){
            $poster = $this->getBaseUrl().'/uservideo/'.$video->id."/{$name}.jpg";
        }
        $this->data['poster'] = $poster;
        $this->data['type'] = mime_content_type('uservideo/'.$video->id."/{$video->file_name}");


        $viewModel = viewModel('admin',__CLASS__,__FUNCTION__,$this->data);

        return $viewModel;

    }

    public function serve(Request $request,$id){

        $video = Video::find($id);

        $path = $this->videoPath.'/'.$video->id.'/'.$video->file_name;

        if ($fp = fopen($path, "rb")) {
            $size = filesize($path);
            $type = mime_content_type($path);
            $length = $size;
            $start = 0;
            $end = $size - 1;
            header('Content-type: '.$type);
            header("Accept-Ranges: 0-$length");
            if (isset($_SERVER['HTTP_RANGE'])) {
                $c_start = $start;
                $c_end = $end;
                list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
                if (strpos($range, ',') !== false) {
                    header('HTTP/1.1 416 Requested Range Not Satisfiable');
                    header("Content-Range: bytes $start-$end/$size");
                    exit;
                }
                if ($range == '-') {
                    $c_start = $size - substr($range, 1);
                } else {
                    $range = explode('-', $range);
                    $c_start = $range[0];
                    $c_end = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $size;
                }
                $c_end = ($c_end > $end) ? $end : $c_end;
                if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size) {
                    header('HTTP/1.1 416 Requested Range Not Satisfiable');
                    header("Content-Range: bytes $start-$end/$size");
                    exit;
                }
                $start = $c_start;
                $end = $c_end;
                $length = $end - $start + 1;
                fseek($fp, $start);
                header('HTTP/1.1 206 Partial Content');
            }
            header("Content-Range: bytes $start-$end/$size");
            header("Content-Length: ".$length);
            $buffer = 1024 * 8;
            while(!feof($fp) && ($p = ftell($fp)) <= $end) {
                if ($p + $buffer > $end) {
                    $buffer = $end - $p + 1;
                }
                set_time_limit(0);
                echo fread($fp, $buffer);
                flush();
            }
            fclose($fp);
            exit();
        } else {
            die('file not found');
        }

    }

    private function setCookie($name, $val, $domain) {
        // using our own implementation because
        // using php setcookie means the values are URL encoded and then AWS CF fails
        header ( "Set-Cookie: $name=$val; path=/; domain=$domain; secure; httpOnly", false );
    }

    public function disk(Request $request){


        $this->data['pageTitle'] = __lang('disk-space-usage');

        //get total videos
        $this->data['total'] = Video::count();

        $space = filesize_r($this->videoPath);

        //get folder size
        $this->data['diskUsage'] = formatSizeUnits($space);


        return view('admin.video.disk',$this->data);
    }




    public function edit(Request $request,$id){
        $output = array();
        $videoTable = new VideoTable();
        $form = $this->getVideoForm();

        $row = $videoTable->getRecord($id);
        if (request()->isMethod('post')) {

            $data = request()->all();
            $form->setData(array_merge_recursive(
                $data,
                $_FILES
            ));
            //  $file =  $_FILES['files'];

            // $form->setData($data);
            if ($form->isValid()) {

                //add groups
                $array = $form->getData();


                $array[$videoTable->getPrimary()]=$id;

                //store dp
                if(!empty($array['picture']['name'])){

                    $videoImage = $this->videoPath.'/'.$row->id.'/'.videoImage($row->file_name);

                    @unlink($videoImage);
                    $format = 'jpg';
             /*       $imageThumb = $this->getServiceLocator()->get('WebinoImageThumb');
                    $thumb = $imageThumb->create(
                        $array['picture']['tmp_name'],
                        $options = array());
                    $thumb->resize(500, 500);
                    $thumb->save($videoImage,strtoupper($format));*/
                    $img = Image::make($array['picture']['tmp_name']);
                    $img->resize(500,500);
                    $img->save($videoImage);

                }

                unset($array['picture']);
                $videoTable->saveRecord($array);
                //    flashMessage('Changes saved!');
                $output['flash_message'] = __lang('Changes Saved');
                $row = $videoTable->getRecord($id);
                session()->flash('flash_message',__lang('Changes Saved'));
                return adminRedirect(array('controller'=>'video','action'=>'index'));

            }
            else{
                $form->setData($data);
                $output['flash_message'] = $this->getFormErrors($form);
            }

        }
        else {

            $data = getObjectProperties($row);

            //get group records
            $groups = [];

            $form->setData($data);



        }

        //check if video image exists
        $picture = false;
        if (file_exists($this->videoPath.'/'.$row->id.'/'.videoImage($row->file_name))){
            $picture = $this->videoDir.'/'.$row->id.'/'.videoImage($row->file_name);
        }

        $output['form'] = $form;
        $output['id'] = $id;
        $output['pageTitle']= __lang('edit-video').': '.$row->name;
        $output['row']= $row;
        $output['action']='edit';
        $output['picture'] = $picture;

        $viewModel = viewModel('admin',__CLASS__,__FUNCTION__,$output);
        return $viewModel ;

    }


    private function getVideoForm(){
        $form = new BaseForm();
        $form->createText('name','Name',true);
        $form->createTextArea('description','Description',false);
        $form->get('description')->setAttribute('id','description');
        $form->createText('length','length',false);

        $file = new File('picture');
        $file->setLabel(__lang('change-thumbnail'))
            ->setAttribute('id','picture');
        $form->add($file);

        $form->setInputFilter($this->getVideoFilter());
        return $form;
    }

    private function getVideoFilter(){
        $filter = new InputFilter();

        $filter->add([
            'name'=>'name',
            'required'=>true,
            'validators'=>[
                [
                    'name'=>'NotEmpty'
                ]
            ]
        ]);

        $filter->add([
            'name'=>'description',
            'required'=>false,
        ]);

        $filter->add([
            'name'=>'length',
            'required'=>false,
        ]);

        $input = new Input('picture');
        $input->setRequired(false);
        $input->getValidatorChain()
            ->attach(new Size(5000000))
            ->attach(new Extension('jpg,png,gif,jpeg'));

        $filter->add($input);

        return $filter;

    }
}
