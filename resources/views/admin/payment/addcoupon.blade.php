@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.payment.coupons')=>__lang('coupons'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('content')

    <div class="card">
    <div class="card-body">
        <form class="form" action="{{selfURL()}}" method="post">
            @csrf
            <div class="form-group">
                <div class="row">
                    <div class="col-md-2">{{formLabel($form->get('name'))}}</div>
                    <div class="col-md-10"> {{formElement($form->get('name'))}}</div>
                </div>


            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-2">{{formLabel($form->get('code'))}}</div>
                    <div class="col-md-10">{{formElement($form->get('code'))}}
                        <p class="help-block">{{ __lang('coupon-code-help') }}</p></div>
                </div>


            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-2">{{formLabel($form->get('type'))}}</div>
                    <div class="col-md-10">{{formElement($form->get('type'))}}
                        <p class="help-block">{{ __lang('coupon-type-help') }}</p></div>
                </div>


            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-2">{{formLabel($form->get('discount'))}}</div>
                    <div class="col-md-10">{{formElement($form->get('discount'))}}</div>
                </div>



            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-2">{{formLabel($form->get('date_start'))}}</div>
                    <div class="col-md-10">{{formElement($form->get('date_start'))}}</div>
                </div>


            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-2">{{formLabel($form->get('expires'))}}</div>
                    <div class="col-md-10">{{formElement($form->get('expires'))}}</div>
                </div>


            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-2">{{formLabel($form->get('total'))}}</div>
                    <div class="col-md-10">{{formElement($form->get('total'))}}
                        <p class="help-block">{{ __lang('coupon-total-help') }}</p></div>
                </div>


            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-2">{{formLabel($form->get('uses_total'))}}</div>
                    <div class="col-md-10">{{formElement($form->get('uses_total'))}}
                        <p class="help-block">{{ __lang('coupon-uses-total-help') }}</p></div>
                </div>


            </div>
            @php if(false):  @endphp
            <div class="form-group">
                <div class="row">
                    <div class="col-md-2">{{formLabel($form->get('uses_customer'))}}</div>
                    <div class="col-md-10">{{formElement($form->get('uses_customer'))}}
                        <p class="help-block">{{ __lang('coupon-uses-customer-help') }}</p></div>
                </div>


            </div>
            @php endif;  @endphp

            <div class="form-group">
                <div class="row">
                    <div class="col-md-2">{{formLabel($form->get('sessions[]'))}}</div>
                    <div class="col-md-10">{{formElement($form->get('sessions[]'))}}
                        <p class="help-block">{{ __lang('coupon-sessions-help') }}</p>
                    </div>
                </div>

            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-2">{{formLabel($form->get('categories[]'))}}</div>
                    <div class="col-md-10">{{formElement($form->get('categories[]'))}}
                        <p class="help-block">{{ __lang('coupon-categories-help') }}</p></div>
                </div>


            </div>








            <div class="form-group">
                <div class="row">
                    <div class="col-md-2">{{formLabel($form->get('enabled'))}}</div>
                    <div class="col-md-10">{{formElement($form->get('enabled'))}}</div>
                </div>

            </div>
            <button class="btn btn-primary">{{ __lang('submit') }}</button>
        </form>

    </div>
    </div>

@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('client/vendor/pickadate/themes/default.date.css') }}">
    <link rel="stylesheet" href="{{ asset('client/vendor/pickadate/themes/default.time.css') }}">
    <link rel="stylesheet" href="{{ asset('client/vendor/pickadate/themes/default.css') }}">
    <link rel="stylesheet" href="{{ asset('client/vendor/datatables/media/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('client/vendor/maxlength/jquery.maxlength.min.css') }}">
@endsection

@section('footer')
    <script type="text/javascript" src="{{ asset('client/vendor/pickadate/picker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('client/vendor/pickadate/picker.date.js') }}"></script>
    <script type="text/javascript" src="{{ asset('client/vendor/pickadate/picker.time.js') }}"></script>
    <script type="text/javascript" src="{{ asset('client/vendor/pickadate/legacy.js') }}"></script>

    <script>
        $(function(){
            $('.date').pickadate({
                format: 'yyyy-mm-dd'
            });
        });
    </script>
@endsection
