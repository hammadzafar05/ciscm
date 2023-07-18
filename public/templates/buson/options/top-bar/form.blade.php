@include('admin.partials.text-input',['name'=>'office_address','label'=>__t('office-address')])
@include('admin.partials.text-input',['name'=>'email','label'=>__t('email')])
@include('admin.partials.color-picker',['name'=>'bg_color','label'=>__('te.background-color')])
@include('admin.partials.color-picker',['name'=>'font_color','label'=>__('te.font-color')])

<h5>@lang('te.social')</h5>
@foreach(['facebook','twitter','instagram','youtube','linkedin'] as $value)
    @include('admin.partials.text-input',['name'=>'social_'.$value,'label'=>ucfirst($value)])
@endforeach








