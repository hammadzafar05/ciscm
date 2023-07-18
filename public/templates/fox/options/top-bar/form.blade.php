
@include('admin.partials.color-picker',['name'=>'bg_color','label'=>__('te.background-color')])
@include('admin.partials.color-picker',['name'=>'font_color','label'=>__('te.font-color')])

@include('admin.partials.select',['name'=>'order_button','label'=>__t('order-button'),'options'=>array('1'=>__('default.enabled'),'0'=>__('default.disabled'))])






