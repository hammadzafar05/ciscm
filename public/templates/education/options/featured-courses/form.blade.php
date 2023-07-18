@include('admin.partials.text-input',['name'=>'sub_heading','label'=>__('te.sub-heading')])
@include('admin.partials.text-input',['name'=>'heading','label'=>__('te.heading')])
@php
    $options =[];
    foreach(\App\Course::latest()->limit(1000)->get() as $course){
        $options[$course->id] = $course->name;
    }
@endphp

@include('admin.partials.select-multiple',['name'=>'courses','label'=>__('default.courses'),'options'=>$options])


