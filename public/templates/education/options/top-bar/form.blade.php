@include('admin.partials.text-input',['name'=>'email','label'=>__t('email')])
@include('admin.partials.text-input',['name'=>'telephone','label'=>__lang('telephone')])

@include('admin.partials.color-picker',['name'=>'bg_color','label'=>__('te.background-color')])
@include('admin.partials.color-picker',['name'=>'font_color','label'=>__('te.font-color')])

@include('admin.partials.select',['name'=>'cart','label'=>__t('cart-button'),'options'=>array('1'=>__('default.enabled'),'0'=>__('default.disabled'))])


<h5>@lang('te.social')</h5>
@include('admin.partials.color-picker',['name'=>'social_bg_color','label'=>__t('social_background-color')])
<hr>
@foreach(['facebook','twitter','instagram','youtube','linkedin'] as $value)
    @include('admin.partials.text-input',['name'=>'social_'.$value,'label'=>ucfirst($value)])
@endforeach








