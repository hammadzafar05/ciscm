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
            <td>{{  __lang('Amount')  }}:</td>
            <td>{{  __lang('Enrollment for')  }} {{  __lang('course-session')  }}</td>

        </tr>


        <tr>
            <td>&nbsp;</td>
            <td>



                <div class="buttons">
                    <div class="right">

                        @php  if ($sandbox) {  @endphp
                            <div class="warning" style="color: red">{{  __lang('payfast-warning')  }} </div>
                        @php  }  @endphp
                        <form action="{{  $action }}" method="post">
                            <input type="hidden" name="merchant_id" value="{{  $merchant_id }}" />
                            <input type="hidden" name="merchant_key" value="{{  $merchant_key }}" />
                            <input type="hidden" name="amount" value="{{  $amount }}" />
                            <input type="hidden" name="item_name" value="{{  $item_name }}" />
                            <input type="hidden" name="item_description" value="{{  $item_description }}" />
                            <input type="hidden" name="name_first" value="{{  $name_first }}" />
                            <input type="hidden" name="name_last" value="{{  $name_last }}" />
                            <input type="hidden" name="email_address" value="{{  $email_address }}" />
                            <input type="hidden" name="return_url" value="{{  $return_url }}" />
                            <input type="hidden" name="notify_url" value="{{  $notify_url }}" />
                            <input type="hidden" name="cancel_url" value="{{  $cancel_url }}" />
                            <input type="hidden" name="custom_str1" value="{{  $custom_str1 }}" />
                            <input type="hidden" name="m_payment_id" value="{{  $m_payment_id }}" />
                            <input type="hidden" name="signature" value="{{  $signature }}" />
                            <div class="buttons">
                                <div class="right">
                                    <input type="submit" value="{{  __lang('pay-now')  }}" class="btn btn-primary" />
                                </div>
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
