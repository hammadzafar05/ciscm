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

<div class="container" style="min-height: 100px;   padding-bottom:50px; margin-bottom: 10px;   " >
@php  if(!$loggedIn): @endphp
    <div class="page-header">
        <h1>{{ $pageTitle }}</h1>
    </div>
@php  endif;  @endphp
    @php  if(isset($message) && false): @endphp
        <div class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ $message }}
        </div>


    @php  endif;  @endphp
    <div class="row" style="background-color: white; ">
        <div class="col-sm-12 col-md-12 ">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>{{  __lang('item')  }}</th>
                    <th class="text-center">{{  __lang('total')  }}</th>
                    <th> </th>
                </tr>
                </thead>
                <tbody>
            @php  foreach(getCart()->getSessions() as $session): @endphp
                <tr>
                    <td class="col-sm-8 col-md-6" style="padding-top: 10px;">
                        <div class="media">

                            @php
                            if($session->session_type=='c'){
                                $url= $this->url('course-details',['id'=>$session->session_id,'slug'=>safeUrl($session->session_name)]);
                            }
                            else{
                                $url = $this->url('session-details',['id'=>$session->session_id,'slug'=>safeUrl($session->session_name)]);
                            }

                             @endphp

                            @php  if(!empty($session->picture)):  @endphp


                                <a class="thumbnail pull-left" href="{{  $url }}"> <img class="media-object" src="{{  resizeImage($session->picture,72,72,url('/')) }}" style="width: 72px; height: 72px;"> </a>

                            @php  endif;  @endphp



                            <div class="media-body">
                                <h4 class="media-heading"><a href="{{ $url }}">{{ $session->session_name }}</a></h4>

                                <span></span><span class="text-success"><strong>@php
                                        switch($session->session_type){
                                            case 'b':
                                                echo __lang('training-online');
                                                break;
                                            case 's':
                                                echo __lang('training-session');
                                                break;
                                            case 'c':
                                                echo __lang('online-course');
                                                break;
                                        }
                                         @endphp</strong></span>
                            </div>
                        </div></td>

                    <td class="col-sm-1 col-md-1 text-center" style="padding-top: 10px;"><strong>{{ price($session->amount) }}</strong></td>
                    <td class="col-sm-1 col-md-1" style="padding-top: 10px;">

                        <a class="btn btn-danger" href="{{ $this->url('remove-session',['id'=>$session->session_id]) }}"><span class="glyphicon glyphicon-remove"></span> {{  __lang('remove')  }}</a>

                    </td>
                </tr>
            @php  endforeach;  @endphp



                </tbody>
            </table>
            @php  if(getCart()->hasItems()): @endphp
            <div class="row">
                <div class="col-md-3">
                    <div class="panel panel-primary">
                        <div class="panel-heading">{{  __lang('coupon')  }}</div>
                        <div class="panel-body">
                            <form method="post" class="form" action="{{  $this->url('cart')  }}">
                                <div class="form-group">
                                    <label for="code">{{  __lang('coupon-code')  }}</label>
                                    <input required="required" class="form-control" type="text" name="code" placeholder="{{  __lang('enter-coupon-code')  }}"/>
                                </div>
                                <button type="submit" class="btn btn-primary">{{  __lang('apply')  }}</button>
                            </form>
                        </div>
                    </div>

                </div>
                <form action="{{ $this->url('shopping-cart/default',['action'=>'checkout']) }}" method="post">
                <div class="col-md-5">
                    <div class="panel panel-success">
                        <div class="panel-heading" style="color: white;">{{  __lang('payment-method')  }}</div>
                        <div class="panel-body">
                            <table class="table table-striped">
                                @php  $count = 0;  @endphp
                                @php  foreach($paymentMethods as $method): @endphp
                                    <tr>
                                        <td><input id="{{ $method->code }}"  @php  if($count==0): @endphp  checked="checked" @php  endif;  @endphp required="required" type="radio" name="payment_method" value="{{  $method->payment_method_id  }}"/> </td>
                                        <td><label for="{{ $method->code }}">{{  $method->method_label  }}</label></td>
                                    </tr>
                                    @php  $count++;  @endphp
                                @php  endforeach;  @endphp
                            </table>
                        </div>
                    </div>

                </div>
                <div class="col-md-4 ">
                    <table class="table table-hover">
                        @php  if(getCart()->hasDiscount()): @endphp
                        <tr>
                            <td>{{  __lang('discount')  }}</td>
                            <td>@php  if(getCart()->discountType()=='P'):  @endphp{{ getCart()->getDiscount() }}%@php  else:  @endphp
                                    {{ price(getCart()->getDiscount()) }}
                                @php  endif;  @endphp<a href="{{ $this->url('shopping-cart/default',['action'=>'removecoupon']) }}">{{  strtolower(__lang('remove'))  }}</a></td>
                        </tr>
                    @php  endif;  @endphp
                        <tr>

                            <td><h3>{{  __lang('total')  }}</h3></td>
                            <td class="text-right"><h3><strong>{{ price(getCart()->getCurrentTotal()) }}</strong></h3></td>
                        </tr>
                        <tr>

                            <td colspan="2">
                                <div class="row">
                                    <div   style="margin-bottom: 10px">
                                        <a class="btn btn-default btn-block" href="{{ url('/') }}/">
                                            <span class="glyphicon glyphicon-shopping-cart"></span> {{  __lang('continue-shopping')  }}
                                        </a>

                                    </div>
                                    <div  >
                                        <button type="submit" class="btn btn-success btn-block">
                                            {{  __lang('checkout')  }} <span class="glyphicon glyphicon-play"></span>
                                        </button>
                                    </div>
                                </div>


                                </td>
                        </tr>
                        </table>
                </div>
                </form>
            </div>
            @php  endif;  @endphp
        </div>
    </div>



</div>
@endsection
