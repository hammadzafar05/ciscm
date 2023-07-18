@extends('layouts.student')
@section('pageTitle',$pageTitle)
@section('innerTitle',$pageTitle)
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('student.dashboard')=>__lang('dashboard'),
            '#'=>$pageTitle
        ]])
@endsection

@section('content')
<div class="card">

    <div class="card-body no-padding">
        <table class="table table-hover table-striped no-margin">
            <thead>
            <tr>
                <th>#</th>
                <th>{{  __lang('Items')  }}</th>
                <th>{{  __lang('Payment Method')  }}</th>
                <th>{{  __lang('Amount')  }}</th>
                <th>{{  __lang('Currency')  }}</th>
                <th>{{  __lang('Created On')  }}</th>
                <th style="min-width: 150px">{{  __lang('Status')  }}</th>
                <th >{{  __lang('Actions')  }}</th>
            </tr>

            </thead>
            <tbody>



            @php  foreach($paginator as $row):  @endphp
                <tr>
                    <td>#{{  $row->id }}</td>
                    <td><a  class="btn btn-primary" role="button" data-toggle="collapse" href="#collapseExample{{ $row->id }}" aria-expanded="false" aria-controls="collapseExample{{ $row->id }}">
                            @php
                            $cart = unserialize($row->cart);
                            try{

                                echo $cart->getTotalItems().' '.ucwords(__lang('items'));
                            }
                            catch(\Exception $ex){
                                echo '0 '.ucwords(__lang('items'));
                            }
                             @endphp <span class="caret"></span></a>
                    </td>
                    <td>
                        @if($row->paymentMethod)
                        {{  $row->paymentMethod->label }}
                        @endif
                    </td>
                    <td>

                        @php
                            $currency_code = 'USD';
                            $currency = \App\Currency::with('country')->find($row->currency_id);
                            if ($currency) $currency_code = $currency->country->currency_code;
                        @endphp
                        {{formatCurrency($row->amount,$currency_code)}}</td>
                    <td>{{ $currency_code }}</td>

                    {{--<td>

                        {{formatCurrency($row->amount,$row->currency->country->currency_code)}}

                    </td>
                    <td>{{  $row->currency->country->currency_code }}</td>--}}

                    <td>{{  showDate('d/M/Y',$row->created_at) }}</td>
                    <td>

                            @php  if($row->paid == 1):  @endphp
                               <span class="color bg-success text-white pl-3 pr-3">{{  __lang('paid')  }}</span>
                        @php  else:  @endphp
                        <span class="color bg-danger text-white pl-3 pr-3">{{  __lang('unpaid')  }}</div>
                            @php  endif;  @endphp

                    </td>
                    <td>
                        @php  if($row->paid == 0):  @endphp
                            <a   href="{{  route('student.student.payinvoice',array('id'=>$row->id)) }}" class="btn  btn-primary " data-toggle="tooltip" data-placement="top" data-original-title="{{  __lang('Pay Now')  }}"><i class="fa fa-money-bill"></i> {{  __lang('Pay Now')  }}</a>
                        @php  endif;  @endphp

                    </td>
                </tr>
                <tr>
                    <td style="height: 0px" colspan="9">
                        <div class="collapse" id="collapseExample{{ $row->id }}">
                            @php  if(is_object($cart)): @endphp
                                <div class="well">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>{{  __lang('course-session')  }}</th>
                                            <th>{{  __lang('Fee')  }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @php  foreach($cart->getSessions() as $session): @endphp
                                            <tr>
                                                <td>{{ $session->name }}</td>
                                                <td>{{  price($session->fee,$row->currency_id) }}</td>
                                            </tr>

                                        @php  endforeach;  @endphp

                                        </tbody>
                                    </table>
                                    @php  if($cart->hasDiscount()): @endphp
                                        <p>
                                            <strong>{{  __lang('Discount')  }}:</strong> {{ $cart->getDiscount() }}% <br/>
                                            @php  if(\App\Coupon::find($cart->getCouponId())):  @endphp
                                                <strong>{{  __lang('Coupon Code')  }}:</strong> {{ \App\Coupon::find($cart->getCouponId())->code }}
                                            @php  endif;  @endphp
                                        </p>
                                    @php  endif;  @endphp
                                </div>
                            @php  endif;  @endphp
                        </div>
                    </td>
                </tr>
            @php  endforeach;  @endphp





            </tbody>
        </table>
        <div>{{ $paginator->links() }}</div>

    </div><!--end .box-body -->
</div><!--end .box -->

@endsection
