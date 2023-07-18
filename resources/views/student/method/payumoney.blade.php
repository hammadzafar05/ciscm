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
            <td>{{  __lang('Enrollment')  }} for {{  'Course/Session' }}</td>

        </tr>


        <tr>
            <td>&nbsp;</td>
            <td>



                <div class="buttons">
                    <div class="right">

                        @php  if ($payumoney_sandbox==1) {  @endphp
                            <div class="warning" style="color: red">{{  __lang('payu-money-warning')  }}</div>
                        @php  }  @endphp

                        <form action="{{  $action  }}" method="post" id="payu_form" name="payu_form" >
                            <input type="hidden" name="key" value="{{  $key }}" />
                            <input type="hidden" name="txnid" value="{{  $tid }}" />
                            <input type="hidden" name="amount" value="{{  $amount }}" />
                            <input type="hidden" name="productinfo" value="{{  $productinfo }}" />
                            <input type="hidden" name="firstname" value="{{  $firstname }}" />
                            <input type="hidden" name="lastname" value="{{  $lastname }}" />
                            <input type="hidden" name="email" value="{{  $email }}" />
                            <input type="hidden" name="phone" value="{{  $phone }}" />
                            <input type="hidden" name="surl" value="{{  $surl }}" />
                            <input type="hidden" name="furl" value="{{  $furl }}" />
                            <input type="hidden" name="hash" value="{{  $hash }}" />
                            <input type="hidden" name="service_provider" value="{{  $service_provider }}" />


                            <div class="buttons">
                                <div ><input type="submit" value="{{  __lang('Pay Now')  }}" class="btn btn-primary btn-lg" /></div>
                            </div>
                        </form>

                    </div>
                </div>










            </td>

        </tr>


        </tbody>
    </table>


</div>
@endsection
