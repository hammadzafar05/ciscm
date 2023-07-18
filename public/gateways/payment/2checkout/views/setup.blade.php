@include('admin.partials.text-input',['name'=>'account_number','label'=>__lang('account-number')])
@include('admin.partials.text-input',['name'=>'secret_word','label'=>__lang('secret-word')])
@include('admin.partials.select',['name'=>'mode','label'=>__lang('mode'),'options'=>['1'=>__lang('live'),'0'=>__lang('test')]])

