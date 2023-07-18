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
@php
$form->prepare();
$form->setAttribute('action', adminUrl(array('controller'=>'setting','action'=>'index')));
$form->setAttribute('method', 'post');
$form->setAttribute('role', 'form');
$form->setAttribute('class', 'form-horizontal');

echo $this->form()->openTag($form);
@endphp


<div class="row">
    <div >
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs" data-toggle="tabs">
                    <li class="active"><a href="tabs-accordions#general"><i class="fa fa-fw fa-gear"></i> General</a></li>
                    <li><a href="tabs-accordions#send"><i class="fa fa-fw fa-forward"></i> Send</a></li>
                    <li><a href="tabs-accordions#deleted"><i class="fa fa-fw fa-trash"></i> Deleted</a></li>
                </ul>
            </div>
            <div class="box-body tab-content">
                <div class="tab-pane active" id="general">
                    <div class="form-group">
                        <div class="col-sm-2">
                            {{ formLabel($form->get('country_id'));   }}
                        </div>
                        <div class="col-sm-10">

                                {{ formElement($form->get('country_id'));    }}

                        </div>
                    </div>
@php foreach($settings as $row): @endphp
    @php if(preg_match('#general_#',$row->key)): @endphp
                    <div class="form-group">
                        <div class="col-sm-2">
                        {{ formLabel($form->get($row->key));   }}
                            </div>
                        <div class="col-sm-10">
                            @php if($row->type == 'radio'): @endphp
                                {{ $this->formRadio($form->get($row->key));    }}
                            @php else: @endphp
                            {{ formElement($form->get($row->key));    }}
                            @php endif;  @endphp
                        </div>
                    </div>
        @php endif;  @endphp
   @php endforeach;  @endphp



                </div>
                <div class="tab-pane" id="send">						<p>Per at postea mediocritatem, vim numquam aliquid eu, in nam sale gubergren. Fuisset delicatissimi duo, qui ut animal noluisse erroribus. Ea eum veniam audire. Dicant vituperata consequuntur.</p>
                </div>
                <div class="tab-pane" id="deleted">						<p>Duo semper accumsan ea, quidam convenire cum cu, oportere maiestatis incorrupte est eu. Soluta audiam timeam ius te, idque gubergren forensibus ad mel, persius urbanitas usu id. Civibus nostrum fabellas mea te, ne pri lucilius iudicabit. Ut cibo semper vituperatoribus vix, cum in error elitr. Vix molestiae intellegat omittantur an, nam cu modo ullum scriptorem.</p>
                    <p>Quod option numquam vel in, et fuisset delicatissimi duo, qui ut animal noluisse erroribus. Ea eum veniam audire. Per at postea mediocritatem, vim numquam aliquid eu, in nam sale gubergren. Dicant vituperata consequuntur at sea, mazim commodo</p>
                </div>
            </div>
        </div>
    </div><!--end .col-lg-12 -->
</div>
    <div class="row">
        <div >
<button class="btn btn-primary" type="submit">{{__lang('save-changes')}}</button>
        </div><!--end .col-lg-12 -->
    </div>
 </form>

@php $this->headScript()->prependFile(basePath() . 'client/vendor/ckeditor/ckeditor.js');  @endphp
@php foreach($settings as $row): @endphp
 @php if($row->class == 'rte'): @endphp
    <script type="text/javascript">

        CKEDITOR.replace('rte_'.{{ $row->key }}, {
            filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
        });

    </script>
@php endif;  @endphp

@php endforeach;  @endphp


@endsection
