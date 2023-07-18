@extends('layouts.student')
@section('pageTitle','')
@section('innerTitle','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('student.dashboard')=>__lang('dashboard'),
            '#'=>$pageTitle
        ]])
@endsection

@section('content')
@php  $this->headTitle($pageTitle);  @endphp
<div class="container box">
    <table class="table table-hover table-striped no-margin">

        <tbody>
        <tr>
            <td>{{  __lang('Amount')  }}:</td>
            <td>{{ currentCurrency()->country->symbol_left }}{{  number_format($invoice->amount,2) }}</td>


        </tr>

        <tr>
            <td>{{  __lang('Description')  }}:</td>
            <td>{{  __lang('enrollment-for')  }} {{  __lang('course-session')  }}</td>

        </tr>

        <tr>
            <td>{{  __lang('transaction-id')  }}:</td>
            <td>{{  $tid }}</td>

        </tr>

        <tr>
            <td>&nbsp;</td>
            <td>



                <form id="test_pos" method="POST" action="https://community.ipaygh.com/gateway">
                    <input name="merchant_key" value="{{ trim($ipay_merchant_key) }}" type="hidden">
                    <input name="success_url" value="{{ $this->url('application/default',['controller'=>'callback','action'=>'ipay'],['force_canonical' => true]) }}" type="hidden">
                    <input name="cancelled_url" value="{{ $this->url('application/default',['controller'=>'payment','action'=>'index'],['force_canonical' => true]) }}" type="hidden">
                    <input name="deferred_url" value="{{ $this->url('application/dashboard',[],['force_canonical' => true]) }}" type="hidden">
                    <input name="ipn_url" value="{{ $this->url('application/ipay-ipn',[],['force_canonical' => true]) }}" type="hidden">

                    <input name="total" value="{{ $invoice->amount }}" type="hidden">
                    <input name="invoice_id" id="invoice_id" value="{{  $tid }}" type="hidden">

                    <button class="btn btn-primary" type="submit">{{  __lang('make-payment')  }}</button>
                </form>







            </td>

        </tr>


        </tbody>
    </table>


</div>
@endsection
