<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WidgetValue extends Model
{
    protected $fillable = ['widget_id','value','enabled','sort_order','visibility'];

    public function widget(){
        return $this->belongsTo(Widget::class);
    }
}
