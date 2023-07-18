@include('admin.partials.text-input',['name'=>'heading','label'=>__('te.heading')])
@include('admin.partials.textarea',['name'=>'description','label'=>__lang('description')])
@php
    $options =[];
    foreach(\App\Admin::where('public',1)->limit(1000)->get() as $admin){
        $options[$admin->id] = $admin->user->name.' '.$admin->user->last_name;
    }
@endphp
@include('admin.partials.select-multiple',['name'=>'instructors','label'=>__('default.instructors'),'options'=>$options])


