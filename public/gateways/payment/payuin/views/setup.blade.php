@include('admin.partials.text-input',['name'=>'merchant_key','label'=>__lang('merchant-key')])
@include('admin.partials.text-input',['name'=>'salt','label'=>__lang('salt')])
@include('admin.partials.select',['name'=>'mode','label'=>__lang('mode'),'options'=>['1'=>__lang('live'),'0'=>__lang('sandbox')]])
