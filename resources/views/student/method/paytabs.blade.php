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
            <td>{{  __lang('Enrollment for')  }}  {{ __lang('course-session') }} </td>

        </tr>

        <tr>
            <td>{{  __lang('Transaction ID')  }}:</td>
            <td>{{  $tid }}</td>

        </tr>

        <tr>
            <td>&nbsp;</td>
            <td>
                <div id="btndiv"></div>
                <script src="https://www.paytabs.com/express/v4/paytabs-express-checkout.js"
                        id="paytabs-express-checkout"
                        data-secret-key="{{ trim($paytabs_secret_key) }}"
                        data-merchant-id="{{ trim($merchantid) }}"
                        data-url-redirect="{{ $this->url('application/default',['controller'=>'callback','action'=>'paytabs'],['force_canonical' => true]) }}"
                        data-amount="{{ $invoice->amount }}"
                        data-currency="{{ strtoupper($country->currency_code)  }}"
                        data-title="{{ __lang('payment-for-enrollment') }}"
                        data-product-names="{{ $sessionList }}"
                        data-order-id="{{ $tid }}"
                        data-customer-phone-number="{{ $student->mobile_number }}"
                        data-customer-email-address="{{ $student->email }}"
                        data-customer-country-code="{{ $dialCode }}"
                        data-ui-element-id="btndiv"
                        data-url-cancel="{{ $this->url('cart',[],['force_canonical' => true]) }}"
                >
                </script>







            </td>

        </tr>


        </tbody>
    </table>


</div>

<div id="btndiv"></div>
@endsection
