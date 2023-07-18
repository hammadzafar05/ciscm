<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Delegate;
use Image;

class DeligateController extends Controller
{
    public function viewDelegates(){
        //dd('ok');
	    $pageTitle="To Show Every Deligates";
	    $delegate=Delegate::where('status',1)->paginate(30);
	    return view('admin.delegate.index',compact('pageTitle','delegate'));
	}
	
	
	public function addStoreDelegates(Request $request,$id=null){
	    if($id==''){
	        
	        $delegate=new Delegate;
	        $title="Create Delegate Page";
	    }else{
	        
	        $delegate=Delegate::find($id);
	        $title="Update Delegate Page";
	    }
	    
	    if($request->isMethod('post')){
	        if ($request->hasFile('image')) {
                $image_tmp = $request->file('image');
                if ($image_tmp->isValid()) {
                    // Upload Images after Resize
                    $image_name = $image_tmp->getClientOriginalName();
                    $extension = $image_tmp->getClientOriginalExtension();
                    $imageName = $image_name . '-' . rand(111, 99999) . '.' . $extension;
                    $bpticar_path = 'usermedia/delegate/'.$imageName;
                    Image::make($image_tmp)->resize(150,100)->save($bpticar_path);
                    $delegate->image = $imageName;
                }
            }
            $delegate->save();
	        return redirect('admin/view-our-delegats')->with('Successfull');
	    }
	   
	        return view('admin.delegate.create',compact('title','delegate'));
	}
}
