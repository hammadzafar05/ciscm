@include('admin.partials.text-input',['name'=>'heading','label'=>__('te.heading')])
@include('admin.partials.textarea',['name'=>'description','label'=>__lang('description')])
@php
    $options =[];
    foreach(\App\Course::latest()->limit(1000)->get() as $course){
        $options[$course->id] = $course->name;
    }
@endphp

@include('admin.partials.select-multiple',['name'=>'courses','label'=>__('default.courses'),'options'=>$options])


