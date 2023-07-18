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
        <div class="card" id="methods">
            <div class="card-header">
                <header></header>



            </div>
            <div class="box-body content"  id="to-payment-methods">
                <div class="row" style=" padding-bottom: 10px" >
                    <div class="col-md-5"><input id="search-list" class="search form-control"  data-sort="name" placeholder="{{ __lang('search') }}" /></div>
                    <div class="col-md-3">
                        <button class="sort btn btn-inverse btn-sm btn-block" data-sort="name">
                            {{ __lang('sort-by-name') }}
                        </button>
                    </div>
                    <div class="col-md-4">

                        {{ formElement($select)}}

                    </div>

                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>{{ __lang('name') }}</th>
                        <th>{{ __lang('enabled') }}</th>
                        <th>
                            {{ __lang('installed-currencies') }}
                        </th>
                        <th style="width: 20%">{{ __lang('supported-currencies') }}</th>
                        <th>{{ __lang('sort-order') }}</th>
                        <th class="text-right1" style="width:90px"></th>
                    </tr>
                    </thead>
                    <tbody  class="list">
                    @php foreach($paginator as $row):  @endphp
                        <tr>

                            <td class="name"><strong>{{ $row->payment_method }}</strong></td>
                            <td>{{ boolToString($row->status) }}</td>
                            <td>
                                @php if($row->is_global==1):  @endphp
                                    {{ __lang('all-currencies') }}
                                    @php else:  @endphp
                                    @php foreach(\App\PaymentMethod::find($row->payment_method_id)->paymentMethodCurrencies as $currency): @endphp
                                        {{$currency->currency->country->currency_code }} &nbsp;
                                    @php endforeach;  @endphp
                            @php endif;  @endphp
                            </td>
                            <td class="currency">{{ $row->currency }}</td>
                            <td>{{ $row->sort_order }}</td>


                            <td >
                                <a href="{{ adminUrl(array('controller'=>'payment','action'=>'edit','id'=>$row->payment_method_id)) }}" class="btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{__lang('edit')}}"><i class="fa fa-edit"></i> {{ __lang('edit') }}</a>

                             </td>
                        </tr>
                    @php endforeach;  @endphp

                    </tbody>
                </table>

                @php
                // add at the end of the file after the table
                echo paginationControl(
                // the paginator object
                    $paginator,
                    // the scrolling style
                    'sliding',
                    // the partial to use to render the control
                    null,
                    // the route to link to when a user clicks a control link
                    array(
                        'route' => 'admin/default',
                        'controller'=>'payment',
                        'action'=>'index',
                    )
                );
                @endphp
            </div><!--end .box-body -->
        </div><!--end .box -->
    </div><!--end .col-lg-12 -->
</div>

<script src="{{ basePath() }}/static/list/list.min.js"></script>
@php // $this->headScript()->prependFile(basePath().'/client/vendor/list/list.min.js'); @endphp
<script>
    var options = {
        valueNames: [ 'name','currency' ]
    };

    var listObj = new List('to-payment-methods', options);

    var options = {
        valueNames: [ 'name','currency' ]
    };


    $('#currencyselect').change(function(e){
        $('#search-list').val('');
        var cur = $(this).val();
        if(cur=='')
        {
            listObj.search();
        }
        else
        {
            listObj.search(cur);
        }
    });
    $('#search-list').focus(function(){
        if($('#currencyselect').val() != '')
        {
            $('#currencyselect').val('');
            listObj.search();
        }

    });
</script>
@endsection
