@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('content')
<div>
    <div >
        <div class="card">

            <div class="card-body">



                <form class="form-horizontal" role="form" method="post" action="{{ adminUrl(array('controller'=>'setting','action'=>$action.'role','id'=>$id)) }}">


                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="col-lg-4 col-md-4 col-sm-6">
                                    <label for="password1" class="control-label">{{ __lang('role') }}</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-6">
                                    {{ formElement($form->get('role')) }}
                                </div>
                            </div>
                        </div>

                    </div>






                    <div class="row">
                        <div class="col-md-12"><h3><input type="checkbox" id="select_all" alt="{{ __lang('select-all') }}" title="{{ __lang('select-all') }}"/>{{ __lang('permissions') }}</h3></div>

                    </div>

                    <div class="row">

                        <div class="col-md-12">
                            <table class="table table-stripped">

                                @php foreach($groups as $row):  @endphp

                                    <thead style="color:#ffffff; background-color: #005580;">
                                    <tr>
                                        <th colspan="2">{{ __lang($row->group) }}</th>

                                    </tr>
                                    </thead>


                                    @php foreach($form->getElements() as $element): @endphp
                                        @php if(preg_match('#_'.strtolower(str_replace(' ','_',$row->group)).'_#',$element->getName())): @endphp
                                    <tr>
                                        <td>{{ formElement($form->get($element->getName())) }} </td>
                                        <td>{{ formLabel($form->get($element->getName())) }}</td>

                                    </tr>
                                            @php endif;  @endphp
                                    @php endforeach;  @endphp


                                @php endforeach;  @endphp
                            </table>


                        </div>

                    </div>


                    <div class="form-footer col-lg-offset-1 col-md-offset-2 col-sm-offset-3">
                        <button type="submit" class="btn btn-primary">{{__lang('save-changes')}}</button>
                    </div>
                </form>
            </div>
        </div><!--end .box -->
    </div><!--end .col-lg-12 -->
</div>

<link rel="stylesheet" type="text/css" href="{{ basePath() }}/client/vendor/pickadate/themes/default.date.css" />
<link rel="stylesheet" type="text/css" href="{{ basePath() }}/client/vendor/pickadate/themes/default.css" />
<script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/picker.js"></script>
<script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/picker.date.js"></script>
<script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/legacy.js"></script>
<script type="text/javascript"><!--

    jQuery(function(){
        jQuery('.date').pickadate({
            format: 'yyyy-mm-dd'
        });
    });

    $("#select_all").change(function(){  //"select all" change
        var status = this.checked; // "select all" checked status
        $('.cbox').each(function(){ //iterate all listed checkbox items
            this.checked = status; //change ".checkbox" checked status
        });
    });

    $('.cbox').change(function(){ //".checkbox" change
        //uncheck "select all", if one of the listed checkbox item is unchecked
        if(this.checked == false){ //if this item is unchecked
            $("#select_all")[0].checked = false; //change "select all" checked status to false
        }

        //check "select all" if all checkbox items are checked
        if ($('.cbox:checked').length == $('.cbox').length ){
            $("#select_all")[0].checked = true; //change "select all" checked status to true
        }
    });
    //--></script>

@php $this->headScript()->prependFile(basePath() . 'client/vendor/ckeditor/ckeditor.js')
 @endphp
<script type="text/javascript">

    CKEDITOR.replace('description', {
        filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
        filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
        filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
    });

</script>
@endsection
