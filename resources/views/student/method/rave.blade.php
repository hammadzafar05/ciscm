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

                <form>
                    <a class="flwpug_getpaid"
                       data-PBFPubKey="{{ $pkey }}"
                       data-txref="{{ $tid }}"
                       data-amount="{{ $invoice->amount }}"
                       data-customer_email="{{ $student->email }}"
                       data-customer_phonenumber="{{ $student->mobile_number }}"
                       data-currency="{{ strtoupper($country->currency_code)  }}"
                       data-country="{{ strtoupper($country->iso_code_2)  }}"
                       data-redirect_url="{{ $this->url('application/default',['controller'=>'callback','action'=>'rave'],['force_canonical' => true]) }}"></a>

                    @php  if($mode==0):  @endphp
                    <script type="text/javascript" src="https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
                    @php  else:  @endphp
                    <script type="text/javascript" src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
                    @php  endif;  @endphp
                </form>






            </td>

        </tr>


        </tbody>
    </table>


</div>
@endsection
