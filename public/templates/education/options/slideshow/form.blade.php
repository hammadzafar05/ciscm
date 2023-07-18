@include('admin.partials.color-picker',['name'=>'slider_background','label'=>__('te.background-color')])

@for($i=1;$i <= 10; $i++)

<div class="card">
    <div class="card-header">
        @lang('te.image') {{ $i }}
    </div>
    <div class="card-body">
        @include('admin.partials.image-input',['name'=>'file'.$i,'label'=>__('te.image')])
        <div class="row">
            <div class="col-md-6">
                @include('admin.partials.text-input',['name'=>'slide_heading'.$i,'label'=>__('te.slide-heading')])
            </div>
            <div class="col-md-6">
                @include('admin.partials.color-picker',['name'=>'heading_font_color'.$i,'label'=>__t('heading-color')])
            </div>
        </div>


        <div class="row">
            <div class="col-md-6">
                @include('admin.partials.text-input',['name'=>'slide_text'.$i,'label'=>__('te.slide-text')])
            </div>
            <div class="col-md-6">
                @include('admin.partials.color-picker',['name'=>'text_font_color'.$i,'label'=>__t('text-color')])
            </div>
        </div>


        @include('admin.partials.text-input',['name'=>'button_text'.$i,'label'=>__t('button-text')])
        @include('admin.partials.text-input',['name'=>'url'.$i,'label'=>__t('link')])
    </div>
</div>
<hr/>
<br/>

@endfor
