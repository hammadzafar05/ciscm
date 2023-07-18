@extends('layouts.cart')
@section('page-title',__lang('cart'))

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h4>{{ __lang('your-cart') }}</h4>
            <div class="card-header-action">

                <div class="dropdown">
                    <a href="#" data-toggle="dropdown" class="btn btn-warning dropdown-toggle">{{ __lang('select-currency') }}</a>
                    <div class="dropdown-menu">
                        @foreach($currencies as $currency)
                            <a href="{{ route('cart.currency',['currency'=>$currency->id]) }}" class="dropdown-item has-icon">{{ $currency->country->symbol_left }}
                                - {{ $currency->country->currency_name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(getCart()->hasItems())
                <div class="table-responsive">
                    <table class="table table-hover mb-3">
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
                            <td class="col-sm-8 col-md-6 pt-2">
                                <div class="media">

                                    @php
                                        $url= route('course',['course'=>$session->id,'slug'=>safeUrl($session->name)]);

                                    @endphp

                                    @php  if(!empty($session->picture)):  @endphp


                                    <a class="thumbnail float-left" href="{{  $url }}"> <img class="media-object" src="{{  resizeImage($session->picture,72,72,url('/')) }}"
                                                                                             style="width: 72px; height: 72px;"> </a>

                                    @php  endif;  @endphp


                                    <div class="media-body pl-3">
                                        {{--MARUF START--}}
                                        @php
                                            $course_name = explode(' - Batch ',$session->name);
                                            $course_name = @$course_name[0];
                                        @endphp
                                        <h5 class="media-heading"><a href="{{ $url }}">{{ $course_name }}</a></h5>
                                        {{--MARUF END--}}

                                        <span></span><span class="text-success"><strong>@php
                                                    switch($session->type){
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
                                </div>
                            </td>

                            <td class="col-sm-1 col-md-1 text-center pt-2"><strong>{{ price($session->fee) }}</strong></td>
                            <td class="col-sm-1 col-md-1 pt-2">

                                <a class="btn btn-danger" href="{{ route('cart.remove',['course'=>$session->id]) }}"><i class="fa fa-trash"></i> {{  __lang('remove')  }}</a>

                            </td>
                        </tr>
                        @php  endforeach;  @endphp


                        </tbody>
                    </table>
                </div>


                <div class="row">
                    <div class="col-md-3">
                        <div class="card card-primary">
                            <div class="card-header">{{  __lang('coupon')  }} (optional)</div>
                            <div class="card-body">
                                <form method="post" class="form" action="{{  route('cart')  }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="code">{{  __lang('coupon-code')  }} (optional)</label>
                                        <input required="required" class="form-control" type="text" name="code" placeholder="{{  __lang('enter-coupon-code')  }} optional"/>
                                    </div>
                                    <button type="submit" class="btn btn-primary">{{  __lang('apply')  }}</button>
                                </form>
                            </div>
                        </div>

                    </div>


                    <div class="col-md-5">
                        <form action="{{ route('cart.process') }}" method="post" id="cart-form">
                            @csrf
                            @if($cart->requiresPayment())
                                <div class="card card-success">
                                    <div class="card-header">{{  __lang('payment-method')  }}</div>
                                    <div class="card-body">
                                        <table class="table table-striped">
                                            @php  $count = 0;  @endphp
                                            @foreach($paymentMethods as $method)
                                                <tr>
                                                    <td>
                                                        {{--CART PAGE--}}
                                                        <input id="method-{{ $method->payment_method_id }}"
                                                               @php  if($count==0): @endphp  checked="checked" @php  endif;  @endphp
                                                               required="required"
                                                               type="radio"
                                                               name="payment_method"
                                                               value="{{  $method->payment_method_id  }}"
                                                               data-label="{{  $method->label  }}"
                                                               onclick="show_emi('{{  $method->payment_method_id  }}')"
                                                        />
                                                    </td>
                                                    <td><label for="method-{{ $method->payment_method_id }}">{{  $method->label  }} </label></td>
                                                </tr>
                                                @php  $count++;  @endphp
                                            @endforeach
                                        </table>
                                        <div id="emi_status_table" style="display: none">
                                            <hr>
                                            <table class="table">
                                                <tr>
                                                    <td>
                                                        <label for="emi_status">Do You want to use EMI</label>
                                                        <br>
                                                        <input id="emi_status_yes"
                                                                 required="required"
                                                                 type="radio"
                                                                 name="emi_status"
                                                                 value="1"
                                                                 onclick="show_installment(1)"/> Yes
                                                        <br>
                                                        <input id="emi_status_no"
                                                               required="required"
                                                               type="radio"
                                                               name="emi_status"
                                                               value="0"
                                                               onclick="show_installment(0)" checked/> No
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div id="emi_installment_table" style="display: none">
                                            <hr>
                                            <table class="table">
                                                <tr>
                                                    <td>
                                                        <label for="emi_status">EMI Installment</label>
                                                        <select id="emi_installment" class="form-control" name="emi_installment">
                                                            <option value="3">3</option>
                                                            <option value="6">6</option>
                                                            <option value="9">9</option>
                                                            <option value="12">12</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="pt-3">
                                            <hr>
                                            <label class="aiz-checkbox">
                                                <input type="checkbox"
                                                       id="agree_checkbox"
                                                       class="required"
                                                       required="required">
                                                <span class="aiz-square-check"></span>
                                                <span>I agree to the</span>
                                            </label>
                                            <a href="{{ url('terms-and-conditions') }}">Terms and Conditions</a>,
                                            <a href="{{ url('refund-and-return-policy') }}">Return Policy</a> &amp;
                                            <a href="{{ url('privacy-policy') }}">Privacy Policy</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </form>
                    </div>

                    <script>
                        var show_emi = function (val){
                            if (val == '3'){
                                $('#emi_status_table').show();
                            }else{
                                $('#emi_status_table').hide();
                            }
                            $('#emi_installment_table').hide();
                        }
                        var show_installment = function (val){
                            if (val == 1){
                                $('#emi_installment_table').show();
                            }else{
                                $('#emi_installment_table').hide();
                            }
                        }
                    </script>

                    <div class="col-md-4 ">
                        <table class="table table-hover">
                            @if(getCart()->hasDiscount())
                                <tr>
                                    <td>{{  __lang('discount')  }}</td>
                                    <td>@if(getCart()->discountType()=='P') {{ getCart()->getDiscount() }}% @else
                                            {{ price(getCart()->getDiscount()) }}
                                        @endif <a href="{{ route('cart.remove-coupon') }}">{{  strtolower(__lang('remove'))  }}</a></td>
                                </tr>
                            @endif
                            <tr>

                                <td><h3>{{  __lang('total')  }}</h3></td>
                                <td class="text-right"><h3><strong>{{ price(getCart()->getCurrentTotal()) }}</strong></h3></td>
                            </tr>
                        </table>
                        <div class="row">
                            <div class="col-md-6">
                                <a class="btn btn-link btn-block" href="{{ route('courses') }}/">
                                    <i class="fa fa-cart-plus"></i> {{  __lang('continue-shopping')  }}
                                </a>

                            </div>
                            <div class="col-md-6">
                                <button type="button" onclick="$('#cart-form').submit()" class="btn btn-success btn-block">
                                    <i class="fa fa-money-bill"></i> {{  __lang('checkout')  }}
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            @else
                <div class="text-center"><h4>{{ __lang('empty-cart') }}</h4></div>
            @endif
        </div>
    </div>
@endsection
