@extends('layouts.admin')
@section('pageTitle',__lang('payment-methods'))
@section('innerTitle',__lang('payment-methods'))
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>__lang('payment-methods')
        ]])
@endsection

@section('content')

    <div class="card"   id="to-payment-methods">
        <div class="card-header">
            <div class="row" >
                <div class="col-md-5"><input id="search-list" class="search form-control"  data-sort="name" placeholder="{{ __lang('search') }}" /></div>
                <div class="col-md-3">
                    <button class="sort btn btn-primary btn-sm btn-block" data-sort="name">
                        {{ __lang('sort-by-name') }}
                    </button>
                </div>
                <div class="col-md-4">

                    {{ formElement($select)}}

                </div>

            </div>
        </div>
    <div class="card-body">
             <table class="table table-striped">
                 <thead>
                 <tr>
                     <th>{{ __lang('name') }}</th>
                     <th>{{ __lang('enabled') }}</th>
                     <th>
                         {{ __lang('installed-currencies') }}
                     </th>
                     <th>{{ __lang('supported-currencies') }}</th>
                     <th>{{ __lang('sort-order') }}</th>
                     <th></th>
                 </tr>
                 </thead>
                 <tbody   class="list">
                    @foreach($methods as $method)
                        @php
                            $gateway = \App\PaymentMethod::where('directory',$method)->first();
                        @endphp
                        <tr>
                            <td class="name">{{ __(paymentInfo($method)['name']) }}</td>
                            <td>
                                @if($gateway)
                                    {{ boolToString($gateway->enabled) }}
                                @else
                                    {{ __lang('no') }}
                                @endif
                            </td>
                            <td>
                                @if($gateway)
                                    @foreach($gateway->currencies as $currency)
                                        {{ strtoupper($currency->country->currency_code) }}
                                    @endforeach
                                @endif
                            </td>
                            <td class="currency">
                                {{ __(paymentInfo($method)['currencies']) }}
                            </td>
                            <td>
                                @if($gateway)
                                    {{  $gateway->sort_order }}
                                @endif
                            </td>
                            <td>
                                 <div class="button-group dropleft">
                                                       <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                       <i class="fa fa-cogs"></i>  {{ __lang('actions') }}
                                                       </button>
                                                       <div class="dropdown-menu wide-btn">
                                                           @if($gateway && $gateway->enabled==1)
                                                               <a class="dropdown-item" href="{{ route('admin.payment-gateways.edit',['paymentMethod'=>$gateway->id]) }}"><i class="fa fa-edit"></i> @lang('default.edit')</a>
                                                               <a class="dropdown-item" href="{{ route('admin.payment-gateways.uninstall',['paymentMethod'=>$gateway->id]) }}"><i class="fa fa-trash"></i> @lang('default.uninstall')</a>
                                                           @else
                                                               <a class="dropdown-item" href="{{ route('admin.payment-gateways.install',['method'=>$method]) }}"><i class="fa fa-download"></i> @lang('default.install')</a>
                                                           @endif
                                                       </div>
                                                     </div>

                            </td>
                        </tr>
                    @endforeach
                 </tbody>
             </table>
    </div>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('client/vendor/list/list.min.js') }}"></script>
    <script>
        var options = {
            valueNames: [ 'name','currency' ]
        };

        var listObj = new List('to-payment-methods', options);

        var options = {
            valueNames: [ 'name','currency' ]
        };


        $('#currencyselect').change(function(e){
            $('#search-list').val('');
            var cur = $(this).val();
            if(cur=='')
            {
                listObj.search();
            }
            else
            {
                listObj.search(cur);
            }
        });
        $('#search-list').focus(function(){
            if($('#currencyselect').val() != '')
            {
                $('#currencyselect').val('');
                listObj.search();
            }

        });
    </script>
@endsection
