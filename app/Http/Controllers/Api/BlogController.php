<?php

namespace App\Http\Controllers\Api;

use App\BlogPost;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface as Response;

class BlogController extends Controller
{

    public function posts(Request $request){

        $params = $request->all();
        if(isset($params['rows']) && !empty($params['rows']) && $params['rows'] <= 100 ){
            $rowsPerPage = $params['rows'];
        }
        else{
            $rowsPerPage = 30;
        }

        $select = BlogPost::orderBy('publish_date','desc');

        if(isset($params['filter']) && !empty($params['filter'])){
            $filter = $params['filter'];
            $select->whereRaw("MATCH (title,content) AGAINST ('$filter' IN NATURAL LANGUAGE MODE)",[$filter]);
        }

        $data['total'] = $select->count();
        $rowset = $select->paginate(30);
        $data = [];

        // $data['current_page']=(int) (empty($params['page'])? 1 : $params['page']);
//        $data['rows_per_page'] = $rowsPerPage;
        $data += $rowset->toArray();

        $newData = [];
        foreach($data['data'] as $row){
            $row['content'] = strip_tags(limitLength($row['content'],200));
            $row['date']= showDate('d M Y',$row['publish_date']);
            $row['newsflash_id'] = $row['id'];
            $row['picture'] = $row['cover_photo'];
            $newData[]  = $row;
        }

        $data['data'] = $newData;


        return jsonResponse($data);
    }

    public function getPost(Request $request,$id){
        $row = BlogPost::find($id);
        $data = $row->toArray();
        $data['date'] = showDate('d M Y',$data['publish_date']);
        $data['newsflash_id'] = $data['id'];
        $data['picture'] = $data['cover_photo'];
        return jsonResponse($data);
    }


}
