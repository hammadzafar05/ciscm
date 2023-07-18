<h5>{{ __lang('contact-us') }}</h5>
@include('admin.partials.textarea',['name'=>'address','label'=>__lang('address')])
@include('admin.partials.text-input',['name'=>'telephone','label'=>__lang('telephone')])
@include('admin.partials.text-input',['name'=>'email','label'=>__lang('email')])

<hr>
@include('admin.partials.textarea',['name'=>'newsletter-code','label'=>__('te.newsletter-code')])
@include('admin.partials.text-input',['name'=>'credits','label'=>__('te.credits')])
@include('admin.partials.color-picker',['name'=>'bg_color','label'=>__('te.background-color')])
@include('admin.partials.color-picker',['name'=>'font_color','label'=>__('te.font-color')])

<h5>@lang('te.social')</h5>
@foreach(['facebook','twitter','instagram','youtube','linkedin'] as $value)
    @include('admin.partials.text-input',['name'=>'social_'.$value,'label'=>ucfirst($value)])
    @endforeach



