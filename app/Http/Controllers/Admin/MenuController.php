<?php

namespace App\Http\Controllers\Admin;

use App\Article;
use App\CourseCategory;
use App\FooterMenu;
use App\HeaderMenu;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{

    public function headerMenu(){
        return view('admin.menu.header_menu',$this->getLinks());
    }

    public function loadHeaderMenu(){
        $menus = HeaderMenu::where('parent_id',0)->orderBy('sort_order')->get();

        return view('admin.menu.load_header',compact('menus'));
    }

    public function saveHeaderMenu(Request $request){

        $validator = Validator::make($request->all(),
            [
                'name'=>'required',
                'label'=>'required',
                'url'=>'required',
                'type'=>'required',

            ]
        );
        if($validator->fails()){
            return response()->json([
                'error'=> implode(' , ',$validator->messages()->all()),
                'status'=>false
            ]);
        }


        $requestData = $request->all();
        if(empty($request->parent_id)){
            $requestData['parent_id'] =0;
        }

        if(empty($request->name)){
            $requestData['name'] = $request->label;
        }

        if(empty($request->sort_order)){
            $requestData['sort_order']=0;
        }

        $headerMenu = HeaderMenu::create($requestData);
        return response()->json([
           'status'=>true
        ]);

    }

    public function updateHeaderMenu(Request $request,HeaderMenu $headerMenu){
        $validator = Validator::make($request->all(),
            [
                'label'=>'required',
                'sort_order'=>'required',
            ]
        );
        if($validator->fails()){
            return response()->json([
                'error'=> implode(' , ',$validator->messages()->all()),
                'status'=>false
            ]);
        }

        $requestData = $request->all();
        $headerMenu->update($requestData);
        return response()->json([
            'status'=>true
        ]);
    }

    public function deleteHeaderMenu(HeaderMenu $headerMenu){

        $headerMenu->delete();
        return response()->json(['status'=>true]);
    }


    public function footerMenu(){


        return view('admin.menu.footer_menu',$this->getLinks());
    }

    public function loadFooterMenu(){
        $menus = FooterMenu::where('parent_id',0)->orderBy('sort_order')->get();

        return view('admin.menu.load_footer',compact('menus'));
    }

    public function saveFooterMenu(Request $request){

        $validator = Validator::make($request->all(),
            [
                'name'=>'required',
                'label'=>'required',
                'url'=>'required',
                'type'=>'required',

            ]
        );
        if($validator->fails()){
            return response()->json([
                'error'=> implode(' , ',$validator->messages()->all()),
                'status'=>false
            ]);
        }


        $requestData = $request->all();
        if(empty($request->parent_id)){
            $requestData['parent_id'] =0;
        }

        if(empty($request->name)){
            $requestData['name'] = $request->label;
        }

        if(empty($request->sort_order)){
            $requestData['sort_order']=0;
        }

        $footerMenu = FooterMenu::create($requestData);
        return response()->json([
            'status'=>true
        ]);

    }

    public function updateFooterMenu(Request $request,FooterMenu $footerMenu){
        $validator = Validator::make($request->all(),
            [
                'label'=>'required',
                'sort_order'=>'required',
            ]
        );
        if($validator->fails()){
            return response()->json([
                'error'=> implode(' , ',$validator->messages()->all()),
                'status'=>false
            ]);
        }

        $requestData = $request->all();
        $footerMenu->update($requestData);
        return response()->json([
            'status'=>true
        ]);
    }

    public function deleteFooterMenu(FooterMenu $footerMenu){

        $footerMenu->delete();
        return response()->json(['status'=>true]);
    }

    private function getLinks(){
        $pages = [
            [
                'name'=>__('default.home'),
                'url'=> route('homepage', [], false)
            ],
            [
                'name'=> __('default.courses'),
                'url'=> route('courses', [], false)
            ],
            [
                'name'=> __('default.sessions'),
                'url'=> route('sessions', [], false)
            ],
            [
                'name'=>__('default.blog'),
                'url'=> route('homepage', [], false)
            ],
            [
                'name'=>__('default.instructors'),
                'url'=> route('instructors', [], false)
            ],
            [
                'name'=> __('default.contact'),
                'url'=> route('homepage', [], false)
            ],
            [
                'name'=> __('default.privacy-policy'),
                'url'=> route('privacy', [], false)
            ],
            [
                'name'=> __('default.terms-conditions'),
                'url'=> route('terms', [], false)
            ]

        ];

        $articles = [];

        foreach(Article::orderBy('title')->get() as $article){

            $articles[] = [
                'name'=> limitLength($article->title,150),
                'url'=> route('article',['slug'=>$article->slug],false)
            ];

        }

        $categories = [];

        foreach(CourseCategory::orderBy('sort_order')->get() as $category){

            $categories[] = [
                'name'=>limitLength($category->name,150),
                'url'=> route('homepage', [], false).'?category='.$category->id
            ];

        }

        return compact('pages','articles','categories');
    }

}
