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

<div class="row mb-3">
    <div class="col-md-8">
        {{ __lang('coupon-help') }}
    </div>
    <div class="col-md-4">
        <a class="btn btn-primary float-right" href="{{adminUrl(['controller'=>'payment','action'=>'addcoupon'])}}"><i class="fa fa-plus"></i> {{ __lang('Add Coupon') }}</a>

    </div>

</div>

<table class="table table-striped">
    <thead>
    <tr>
        <th>{{ __lang('name') }}</th>
        <th>{{ __lang('code') }}</th>
        <th>{{ __lang('discount') }}</th>
        <th>{{ __lang('enabled') }}</th>
        <th>{{ __lang('starts') }}</th>
        <th>{{ __lang('ends') }}</th>
        <th>{{ __lang('times-used') }}</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
        @php foreach($coupons as $coupon): @endphp
        <tr>
            <td>{{$coupon->name}}</td>
            <td>{{$coupon->code}}</td>
            <td>{{$coupon->discount}}@php if($coupon->type=='P'):  @endphp%@php endif; @endphp</td>
            <td>{{boolToString($coupon->enabled)}}</td>
            <td>{{showDate('d/M/Y',$coupon->date_start)}}</td>
            <td>{{showDate('d/M/Y',$coupon->expires_on)}}</td>
            <td>{{$coupon->invoices()->count()}}</td>
            <td>
                <a class="btn btn-primary" href="{{adminUrl(['controller'=>'payment','action'=>'editcoupon','id'=>$coupon->id          ])}}"><i class="fa fa-edit"></i> {{ __lang('edit') }}</a>
                <a onclick="return confirm('{{__lang('delete-confirm')}}')" class="btn btn-danger" href="{{adminUrl(['controller'=>'payment','action'=>'deletecoupon','id'=>$coupon->id          ])}}"><i class="fa fa-trash"></i> {{ __lang('delete') }}</a>
            </td>
        </tr>
    @php endforeach;  @endphp
    </tbody>
</table>
{{$coupons->links()}}
@endsection
