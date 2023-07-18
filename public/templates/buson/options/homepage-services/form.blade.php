<div class="row">
    @for($i=1;$i <= 2; $i++)
        <div class="col-md-6">
            <div class="card">

                <div class="card-body">
                    <h5 class="card-title">{{ __t('service') }} {{ $i }}</h5>
                    @include('admin.partials.image-input',['name'=>'file'.$i,'label'=>__('te.image')])
                    @include('admin.partials.text-input',['name'=>'heading'.$i,'label'=>__('te.heading')])
                    @include('admin.partials.rte',['name'=>'text'.$i,'label'=>__('te.text')])
                </div>
            </div>
        </div>
    @endfor

</div>

<hr>
<h1>{{ __t('information') }}</h1>
<div class="card">
    <div class="card-body">
        @include('admin.partials.text-input',['name'=>'info_heading','label'=>__('te.heading')])
        @include('admin.partials.rte',['name'=>'info_text','label'=>__('te.text')])
        @include('admin.partials.text-input',['name'=>'button_text','label'=>__t('button-text')])
        @include('admin.partials.text-input',['name'=>'url','label'=>__t('link')])
    </div>
</div>
