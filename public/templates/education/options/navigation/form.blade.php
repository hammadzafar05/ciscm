@include('admin.partials.color-picker',['name'=>'bg_color','label'=>__('te.background-color')])
@include('admin.partials.color-picker',['name'=>'font_color','label'=>__('te.font-color')])
@include('admin.partials.color-picker',['name'=>'logo_bg','label'=>__t('logo-bg')])
@include('admin.partials.select',['name'=>'search_form','label'=>__t('search-form'),'options'=>array('1'=>__('default.enabled'),'0'=>__('default.disabled'))])







