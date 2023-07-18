<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 8/22/2017
 * Time: 11:11 AM
 */

namespace App\Http\Controllers\Student;
use App\Http\Controllers\Controller;
use App\Lib\HelperTrait;
use Illuminate\Http\Request;

use App\V2\Model\DownloadFileTable;
use App\V2\Model\DownloadSessionTable;
use App\V2\Model\DownloadTable;
use App\V2\Model\StudentSessionTable;
use Laminas\EventManager\EventManagerInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;


class DownloadController extends Controller {

    use HelperTrait;

    public function index(Request $request){

        $table = new DownloadTable();
        $downloadFileTable = new DownloadFileTable();
        $downloadSessionTable = new DownloadSessionTable();
        $studentSessionTable = new StudentSessionTable();

        $paginator = $studentSessionTable->getDownloads($this->getId());

        $paginator->setCurrentPageNumber((int)request()->get('page', 1));
        $paginator->setItemCountPerPage(30);
        return viewModel('student',__CLASS__,__FUNCTION__,array(
            'paginator'=>$paginator,
            'pageTitle'=>__lang('Downloads'),
            'downloadTable'=>$table,
            'fileTable'=>$downloadFileTable,
            'sessionTable' => $downloadSessionTable,
            'studentId'=>$this->getId()
        ));

    }

    public function files(Request $request,$id){
        $downloadTable = new DownloadTable();
        $row = $downloadTable->getDownload($id,$this->getId());
        if(!$row){
            flashMessage(__lang('no-download-permission'));
            return redirect()->route('student.download.index');
        }
        $table = new DownloadFileTable();
        $rowset = $table->getDownloadRecords($id);
        $viewModel = viewModel('student',__CLASS__,__FUNCTION__,['rowset'=>$rowset,'pageTitle'=>__lang('File List').': '.$row->name,'id'=>$id,'row'=>$row]);

        return $viewModel;
    }


    public function file(Request $request,$id){
        set_time_limit(86400);
        $table = new DownloadFileTable();
        $row = $table->getFile($id,$this->getId());
        $path = 'usermedia/'.$row->path;



        header('Content-type: '.getFileMimeType($path));

// It will be called downloaded.pdf
        header('Content-Disposition: attachment; filename="'.basename($path).'"');

// The PDF source is in original.pdf
        readfile($path);
        exit();
    }


    public function allfiles(Request $request,$id){
        set_time_limit(86400);

        $downloadTable = new DownloadTable();
        $downloadFileTable= new DownloadFileTable();
        $rowset = $downloadFileTable->getFiles($id,$this->getId());
        $downloadRow = $downloadTable->getRecord($id);

        $zipname = safeUrl($downloadRow->name).'.zip';
        $zip = new \ZipArchive;
        $zip->open($zipname, \ZipArchive::CREATE);
        $count = 1;
        $deleteFiles = [];
        foreach ($rowset as $row) {
            $path = 'usermedia/' . $row->path;

            if (file_exists($path)) {
            $newFile = $count.'-'.basename($path);
                copy($path,$newFile);
            $zip->addFile($newFile);
            $count++;
                $deleteFiles[] = $newFile;
             }



        }
        $zip->close();

        foreach($deleteFiles as $value){
            unlink($value);
        }

        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename='.$zipname);
        header('Content-Length: ' . filesize($zipname));
        readfile($zipname);
        unlink($zipname);
        exit();
    }

}
