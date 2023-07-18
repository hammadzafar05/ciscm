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
            <td>&nbsp;</td>
            <td>



                <form action="{{ $this->url('application/default',['controller'=>'callback','action'=>'stripe'],['force_canonical' => true]) }}" method="POST">
                    <script
                        src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                        data-key="{{  trim($pkey)  }}"
                        data-currency="{{  $currency  }}"
                        data-amount="{{  ($invoice->amount * 100) }}"
                        data-name="{{  setting('general_site_name')  }}"
                        data-description="{{  'Course/Session' }} enrollemnt"
                        data-email="{{  $student->email  }}"
                        data-image="{{  $siteUrl }}{{  resizeImage(setting('image_logo'),128,128,url('/')) }}"
                        data-locale="auto"
                        data-zip-code="true">
                    </script>
                </form>









            </td>

        </tr>


        </tbody>
    </table>


</div>
@endsection
