<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Download;
use App\V2\Model\DownloadFileTable;
use App\V2\Model\DownloadSessionTable;
use App\V2\Model\DownloadTable;
use App\V2\Model\StudentSessionTable;
use Illuminate\Http\Request;
use App\Lib\HelperTrait;
use Psr\Http\Message\ResponseInterface as Response;

class DownloadController extends Controller
{

    use HelperTrait;


    public function downloads(Request $request){

        $table = new DownloadTable();
        $downloadFileTable = new DownloadFileTable();
        $downloadSessionTable = new DownloadSessionTable();
        $studentSessionTable = new StudentSessionTable();

        $paginator = $studentSessionTable->getDownloads($this->getApiStudent()->id);
        $params = $request->all();

        $perPage= 30;
        $page = empty($params['page'])? 1:$params['page'];
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($perPage);

        $output = [];

        $output['per_page'] = $perPage;
        $output['total'] = $studentSessionTable->getDownloadsTotal($this->getApiStudentId());
        $output['current_page']= $page;

        $totalPages= ceil($output['total'] /$perPage);
        $downloadRows = [];
        if($page<=$totalPages){
            foreach($paginator as $row){

                $dRow= Download::find($row->download_id);
                $downloadRows[] = [
                    'download_id'=>$row->download_id,
                    'download_name'=>$row->download_name,
                    'description'=>$dRow->description,
                    'files'=>$downloadFileTable->getTotalForDownload($row->download_id),
                ];
            }
        }

        $output['data'] = $downloadRows;
        return jsonResponse($output);



    }

    public function getDownload(Request $request,$id){
        $downloadTable = new DownloadTable();

        $student = $this->getApiStudent();

        $row = $downloadTable->getDownload($id,$student->id);
        if(!$row){
            return jsonResponse([
                'status'=>false,
                'message'=>'You do not have permission to access this download'
            ]);
        }

        $download = apiDownload(Download::find($id));
        $download->created_on = stamp($download->created_at);
        $download->account_id = $download->admin_id;

        $table = new DownloadFileTable();
        $rowset = $table->getDownloadRecords($id);

        $files = [];
        foreach($rowset as $row){
             $row->download_file_id = $row->id;
             $row->created_on = stamp($row->created_at);
             $files[] = $row;
        }

        $download['files']= $files;

        return jsonResponse([
            'status'=>true,
            'download'=>$download
        ]);

    }



    public function file(Request $request,$id){
        set_time_limit(86400);

        $table = new DownloadFileTable();
        $row = $table->getFile($id,$this->getApiStudent()->id);
        $path = 'usermedia/'.$row->path;
        header('Content-type: '.getFileMimeType($path));
        header('Content-Disposition: attachment; filename="'.basename($path).'"');
        header('Content-Length: '.filesize($path));
        readfile($path);
        exit();
    }


}
