@include('admin.partials.text-input',['name'=>'heading','label'=>__('te.heading')])
@include('admin.partials.rte',['name'=>'text','label'=>__('te.text')])
@include('admin.partials.image-input',['name'=>'image','label'=>__('te.image')])


<hr>
<h3>{{ __t('attributes') }}</h3>
<div class="row">
    @for($i=1;$i <= 6; $i++)
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __t('service') }} {{ $i }}</h5>
                    @include('admin.partials.text-input',['name'=>'icon'.$i,'label'=>__t('icon-class'),'placeholder'=>'fa fa-user'])
                    @include('admin.partials.text-input',['name'=>'heading'.$i,'label'=>__('te.heading')])
                    @include('admin.partials.rte',['name'=>'text'.$i,'label'=>__('te.text')])
                </div>
            </div>
        </div>
    @endfor
</div>


