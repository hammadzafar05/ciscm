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



                <div class="buttons">
                    <div class="right">
                        <a  id="payu-confirm" href="#" class="btn btn-primary"><i class="fa fa-money"></i> {{  __lang('Pay Now')  }}</a>
                    </div>
                </div>
                <script type="text/javascript"><!--
                    $('#payu-confirm').click(function(e) {
                        e.preventDefault();
                        $.ajax({
                            url: '{{ $this->url('application/default',['controller'=>'method','action'=>'payusend','id'=>$session->session_id]) }}',
                            type: 'post',
                            dataType: 'json',
                            cache: false,
                            beforeSend: function() {
                                $('#payu-confirm').hide();
                                $('#payu-confirm').after('<div class="attention">{{ __lang('contacting-payu') }} </div>');
                            },
                            complete: function() {

                            },
                            success: function(json) {
                                if (json['redirect']) {
                                    location.replace(json['redirect']);
                                } else {
                                    alert(json['error']);
                                }
                            }
                        });
                    });
                    //--></script>











            </td>

        </tr>


        </tbody>
    </table>


</div>
@endsection
