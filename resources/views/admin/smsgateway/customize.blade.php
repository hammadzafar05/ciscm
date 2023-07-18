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
$form->setAttribute('action', adminUrl(array('controller'=>'smsgateway','action'=>'customize','id'=>$id)));
$form->setAttribute('method', 'post');
$form->setAttribute('role', 'form');
$form->setAttribute('class', 'form-horizontal');

echo $this->form()->openTag($form);
@endphp
    <div class="row">
        <div >
            <div class="card">

                <div class="card-body">

                    @php foreach($options as $row): @endphp

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

                    @php endforeach;  @endphp





                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div >
            <button class="btn btn-primary" type="submit">{{__lang('save-changes')}}</button>
        </div><!--end .col-lg-12 -->
    </div>

 </form>
@endsection
