@for($i=1;$i <= 6; $i++)

    <div class="card">
        <div class="card-header">
            {{ __t('testimonial') }} {{ $i }}
        </div>
        <div class="card-body">
            @include('admin.partials.text-input',['name'=>'name'.$i,'label'=>__('default.name')])
            @include('admin.partials.text-input',['name'=>'role'.$i,'label'=>__('default.role')])
            @include('admin.partials.image-input',['name'=>'image'.$i,'label'=>__('te.image').' '.$i])
            @include('admin.partials.textarea',['name'=>'text'.$i,'label'=>__('te.text')])
            @include('admin.partials.select',['name'=>'stars'.$i,'label'=>__t('stars'),'options'=>[1=>1,2=>2,3=>3,4=>4,5=>5]])
        </div>
    </div>


@endfor
