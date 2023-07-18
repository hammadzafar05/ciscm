<?php

namespace App\Http\Controllers\Admin;

use App\Helper\AppHelper;
use App\Http\Controllers\Controller;
use App\Model\Page;
use Illuminate\Http\Request;
use Validator;

class PageController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Page Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the creation, viewing, updating, deletion
    |
    |
    */

    public $current_module_link, $page_title, $image_cdn;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $page_title = 'Page';
        $this->current_module_link = 'page';
        $this->page_title = $page_title;
        $this->image_cdn = Page::PAGE_FOLDER;

        $this->middleware('auth');
    }

    /**
     * Show the application view.
     *
     */
    public function index()
    {
        $slug = \Request::route()->getName();
        $slug = 'admin.'.$slug;

        $slug = str_replace('admin.','',$slug);
        $slug = str_replace('setting.','',$slug);

        $slug = str_replace('_','-',$slug);
        $page_title = ucwords(str_replace('-',' ',$slug));

        $page = Page::where('slug', $slug)->first();
        $this->page_title = $page_title;
        $this->current_module_link = $slug;

        $pageTitle = $page_title;
        $output['page_title'] = $page_title;
        $output['slug'] = $slug;
        $output['data'] = $page;

        return view('admin.pages.page',compact('output','pageTitle'));
    }

    /**
     * Store a newly created data/ Edit old data in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rulesForValidation = [
            //'name' => 'required|string|min:3|max:255|unique:pages,name,'.$id,
            'name' => 'required|string|min:3|max:255',
            'description' => 'required|string',
        ];

        $customErrorMessages = [
            'name.required' => 'The :attribute can not be blank.',
            'name.unique' => 'Page Title exists in the system.',
        ];

        $jsonResponse = AppHelper::ValidateData(
            $request->all(),
            $rulesForValidation,
            $customErrorMessages
        );

        if ($jsonResponse !== true) {
            return AppHelper::RespondWithError(
                $jsonResponse,
                '',
                '200'
            );
        }

        $slug = $request->input('existing_slug');

        $data = Page::where('slug', $slug)->first();
        if($data) {
            $old = $data->toArray();

            $data->update($request->all());
            $new = $data->toArray();

            return AppHelper::RespondWithSuccess(
                'Data has been updated successfully',
                $data
            );
        }else{
            $data = Page::create($request->all());

            return AppHelper::RespondWithSuccess(
                'Data save successfully',
                $data
            );
        }
    }
}
