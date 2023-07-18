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
            <td>{{  __lang('Enrollment for')  }} {{  'Course/Session' }}</td>

        </tr>

        <tr>
            <td>{{  __lang('Transaction ID')  }}:</td>
            <td>{{  $tid }}</td>

        </tr>

        <tr>
            <td>&nbsp;</td>
            <td>


                <form id="paymentForm" action="{{ $this->url('application/default',['controller'=>'callback','action'=>'paystack'],['force_canonical' => true]) }}" method="POST" >
                    <script
                        src="https://js.paystack.co/v1/inline.js"
                        data-key="{{  trim($public_key)  }}"
                        data-email="{{  $student->email  }}"
                        data-amount="{{  ($invoice->amount * 100) }}"
                        data-ref="{{  $tid }}"

                        >
                    </script>

                </form>







            </td>

        </tr>


        </tbody>
    </table>


</div>
@endsection
