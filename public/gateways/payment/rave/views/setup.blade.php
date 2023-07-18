@include('admin.partials.text-input',['name'=>'public_key','label'=>__lang('public-key')])
@include('admin.partials.text-input',['name'=>'secret_key','label'=>__lang('secret-key')])
@include('admin.partials.select',['name'=>'mode','label'=>__lang('mode'),'options'=>['1'=>__lang('live'),'0'=>__lang('test')]])
