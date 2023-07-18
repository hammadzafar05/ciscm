@include('admin.partials.text-input',['name'=>'sub_heading','label'=>__('te.sub-heading')])
@include('admin.partials.text-input',['name'=>'main_header','label'=>__('te.heading')])
<hr>
<div class="row">
    @for($i=1;$i <= 6; $i++)
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __t('service') }} {{ $i }}</h5>
                    @include('admin.partials.text-input',['name'=>'icon'.$i,'label'=>__t('icon-class'),'placeholder'=>'fa fa-user'])
                    @include('admin.partials.text-input',['name'=>'heading'.$i,'label'=>__('te.heading')])
                    @include('admin.partials.textarea',['name'=>'text'.$i,'label'=>__('te.text')])
                    @include('admin.partials.text-input',['name'=>'button_text'.$i,'label'=>__t('button-text')])
                    @include('admin.partials.text-input',['name'=>'url'.$i,'label'=>__t('link')])
                </div>
            </div>
        </div>
    @endfor

</div>



