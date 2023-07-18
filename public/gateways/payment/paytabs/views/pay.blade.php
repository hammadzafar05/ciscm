@extends('layouts.checkout')
@section('page-title',$method->label)

@section('page-class')
    class="col-md-8 offset-md-2"
@endsection
@section('payment-content')

    <div class="row">
        <div class="col-md-6">
            <h4>{{ __lang('billing-address') }}</h4>
            <table class="table table-striped">
                <tr>
                    <th>{{ __lang('firstname') }}</th>
                    <td>{{ $user->billing_firstname }}</td>
                </tr>
                <tr>
                    <th>{{ __lang('lastname') }}</th>
                    <td>{{ $user->billing_lastname }}</td>
                </tr>
                <tr>
                    <th>{{ __('default.country') }}</th>
                    <td>
                        @if(\App\Country::find($user->billing_country_id))
                            {{ \App\Country::find($user->billing_country_id)->name }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>{{ __('default.state') }}</th>
                    <td>{{ $user->billing_state }}</td>
                </tr>
                <tr>
                    <th>{{ __('default.city') }}</th>
                    <td>{{ $user->billing_city }}</td>
                </tr>
                <tr>
                    <th>{{ __('default.address-1') }}</th>
                    <td>{{ $user->billing_address_1  }}</td>
                </tr>
                <tr>
                    <th>{{ __('default.address-2') }}</th>
                    <td>{{ $user->billing_address_2 }}</td>
                </tr>
                <tr>
                    <th>{{ __('default.zip') }}</th>
                    <td>{{ $user->billing_zip }}</td>
                </tr>



            </table>

            <button onclick="openModal('{{ addslashes(__lang('billing-address')) }}','{{ route('student.student.billing') }}')" class="btn btn-primary"><i class="fa fa-edit"></i> {{ __lang('edit') }}</button>

        </div>
        <div class="col-md-6">
            <table class="table table-striped">
                <tr>
                    <th>{{ __lang('amount') }}</th>
                    <td>{{ price(getCart()->getCurrentTotal()) }}</td>
                </tr>
                <tr>
                    <th>{{ __lang('invoice-id') }}</th>
                    <td>{{ $invoice->id }}</td>
                </tr>

            </table>
            <div class="text-center row">
                <div class="col-md-6 offset-md-3">
                    <div class="text-center">
                        <form method="post" action="{{ route('cart.method',['function'=>'traineasy_send','code'=>$code]) }}">
                            @csrf
                            <button class="btn btn-lg rounded btn-primary"><i class="fas fa-money-bill"></i> {{ __lang('pay-now') }}</button>
                        </form>
                    </div>

                </div>

            </div>

        </div>
    </div>






@endsection
