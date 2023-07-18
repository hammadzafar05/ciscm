<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Article;
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface as Response;

class ArticlesController extends Controller
{
    public function articles(Request $request){

        $rowset = Article::where('mobile',1)->select('id as article_id','title as article_name','content as article_content','slug as alias')->orderBy('title')->get();

        $data = $rowset->toArray();
        return jsonResponse($data);
    }

    public function getArticle(Request $request,$id){

        $row = Article::select('id as article_id','title as article_name','content as article_content','slug as alias')->find($id);
        $data = $row->toArray();
        return jsonResponse($data);
    }

}
