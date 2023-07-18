@for($i=1;$i <= 4; $i++)

    <div class="card">
        <div class="card-header">
            {{ __lang('image') }} {{ $i }}
        </div>
        <div class="card-body">
            @include('admin.partials.image-input',['name'=>'image'.$i,'label'=>__('te.image').' '.$i])
        </div>
    </div>


@endfor
