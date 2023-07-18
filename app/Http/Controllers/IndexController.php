<?php

namespace App\Http\Controllers;

use App\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use PhpQuery\PhpQuery;

class IndexController extends Controller
{

    public function query(){
        echo safeUrl('ayo d . gee & a fine boy');
    //   echo safeUrl('Самооценка');



    }

    public function lang(){
        $dir    = '../app/Http/Controllers/Student';

        $folders = [
            '../resources/views',
            './templates/buson/views'
//            '../app/Http/Controllers/Student',
//            '../app/Http/Controllers/Admin',
//            '../app/Http/Controllers/Site',
//            '../app/Http/Controllers/Api',
//            '../app/Http/Controllers/Auth',
         //   '../app/Http/Controllers'
        ];
        $langList = [];
        //$dir = './';
        foreach ($folders as $folder){
            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($folder));
            $files = scandir($folder);

            foreach ($iterator as $file){
             /*   if(strlen($file) < 4){
                    continue;
                }

                $path = $folder.'/'.$file;
                if (is_dir($path)){
                    continue;
                }*/
                if ($file->isDir()) continue;
                $path = $file->getPathname();
                $content = file_get_contents($path);
                    do{
                        //get position of starting tag
                        $tag = "@lang('site.";

                        $pos = strpos($content,$tag);
                        if (empty($pos)){
                            continue;
                        }

                        $tempContent = substr($content,$pos);

                        // dd($tempContent);
                        $priorContent = substr($content,0,$pos);

                        //get ending tag
                        $endTag = "'";
                        $endPos = strpos($tempContent,$endTag,7);

                        if (empty($endPos)){
                            echo 'no pos';
                            continue;
                        }
                        $tagString = substr($tempContent,0,$endPos);
                        $key = str_replace($tag,'',$tagString);
                        $langList[$key] = $key;

                     //   echo $tagString.':'.$path.'<br/>';
                        $content = str_replace($tagString,'',$content);
                    }while(substr_count($content,$tag)>0);

            }




        }

        $lang = include '../resources/lang/en/site.php';
        foreach ($langList as $value){
            echo "'{$value}'=>'{$lang[$value]}',
";
        }

    }

    public function misc(){
        $settings = \App\Setting::where('key','LIKE','menu_%')->get();
        foreach ($settings as $setting){
            echo  $setting->key.": {$setting->value} <br/>";
        }
    }


    public function perm(){
        $routes = app('router')->getRoutes();

        $all = $routes->getRoutesByName();
        foreach ($all as $key=>$value){
            if(preg_match('"admin."',$key)){
                echo "'{$key}'=>'',<br/>";
            }

        }
    }


    public function routefix(){
        //get list of all controllers in admin directory
        $dir    = '../app/Http/Controllers/Student';
        //$dir = './';
        $files = scandir($dir);
      //  dd($files);

        foreach($files as $key=>$value){
            if($key < 2){
                continue;
            }

            $fileName = $value;
            //remove .php from name
            $fileName = str_ireplace('.php','',$fileName);
            $className = '\App\Http\Controllers\Student\\'.$fileName;
           // echo $className.'<br/>';
         /*   $methods = get_class_methods($className);
            dd($methods);*/

  /*          $class = new \ReflectionClass($className);
            $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
            var_dump($methods);*/

            $f = new \ReflectionClass($className);
            $methods = array();
            foreach ($f->getMethods() as $m) {

                if ('\\'.$m->class == $className) {
                    $methods[] = $m->name;
                }
            }

            foreach($methods as $method){
                if(preg_match('#Action()#',$method)){
                    $method = str_ireplace('Action','',$method);
                    $routeName = strtolower(str_ireplace('Controller','',$fileName)).'.'.$method;
                    $path = strtolower(str_ireplace('Controller','',$fileName)).'/'.$method;
                    echo "Route::any('{$path}','{$fileName}@{$method}')->name('{$routeName}');<br/>";
                }
            }

        }

    }

    public function extensionchange(){
        //get list of all controllers in admin directory
        $dir    = '/var/www/html/Projects/TrainEasyV3/resources/views/student';
        //$dir = './';
        $folders = scandir($dir);

        unset($folders[0],$folders[1]);
 //       dd($folders);

        foreach($folders as $folder){

            $viewDir = $dir.'/'.$folder;
            echo $viewDir;
            echo '<br/>';
            if (!is_dir($viewDir)){
                echo getcwd();
                echo '<br>No Dir: '.$viewDir.'<br>';
                continue;
            }
            chdir($viewDir);
            exec('rename "s/phtml/blade.php/" *.phtml');
    /*        $files = scandir($viewDir);
            unset($files[0],$files[1]);
            dd($files);*/

        }



    }

    public function echofix(){
        $dir    = '../resources/views/student';
        //$dir = './';
        $folders = scandir($dir);
        unset($folders[0],$folders[1]);
        foreach($folders as $folder){

            $viewDir = $dir.'/'.$folder;

            //echo $viewDir;
            $files = scandir($viewDir);
            unset($files[0],$files[1]);

            foreach($files as $file){
                $fullpath = $viewDir.'/'.$file;
                // echo $fullpath.'<br/>';

                $content = file_get_contents($fullpath);
                $changed = false;
                do{
                     //get position of starting tag
                    $tag = '<?php';
                    $pos = strpos($content,$tag);
                    if (empty($pos)){
                        break;
                    }
                    $tempContent = substr($content,$pos);
                   // dd($tempContent);
                    $priorContent = substr($content,0,$pos);

                    //get ending tag
                    $endTag = '?>';
                    $endPos = strpos($tempContent,$endTag);
                    if (empty($endPos)){
                        echo 'no pos';
                        break;
                    }
                    $appendContent = substr($tempContent,$endPos+2);
                    $tagString = substr($tempContent,0,$endPos+2);
                    $tagString = str_replace($tag,'@php ',$tagString);
                    $tagString = str_replace($endTag,' @endphp',$tagString);

                    $content = $priorContent.$tagString.$appendContent;

                    $changed = true;
                }while(preg_match("#{$tag}#",$content));

               //dd($content);
                if ($changed){

                      file_put_contents($fullpath,$content);
                }



            }


        }

        exit('done');

    }

    public function viewsetup(){
        //get list of all controllers in admin directory
        $dir    = '../resources/views/student';
        //$dir = './';
        $folders = scandir($dir);
        unset($folders[0],$folders[1]);
        //       dd($folders);

        foreach($folders as $folder){

            $viewDir = $dir.'/'.$folder;
            //echo $viewDir;
            $files = scandir($viewDir);
            unset($files[0],$files[1]);

            foreach($files as $file){
                $fullpath = $viewDir.'/'.$file;
               // echo $fullpath.'<br/>';

                $content = file_get_contents($fullpath);
                $pageTitle = '$pageTitle';
                $content = "@extends('layouts.student')
@section('pageTitle','')
@section('innerTitle','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('student.dashboard')=>__('default.dashboard'),
            '#'=>$pageTitle
        ]])
@endsection

@section('content')
{$content}
@endsection
                ";

                file_put_contents($fullpath,$content);


            }
           // dd($files);

        }

        echo 'done';


    }

}
