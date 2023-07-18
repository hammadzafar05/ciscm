@extends('layouts.checkout')
@section('page-title',$method->label)


@section('payment-content')

    <p>
        {!! $instructions !!}
    </p>

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
    <div class="text-center">
        <a href="{{ route('cart.method',['code'=>$code,'function'=>'traineasy_complete']) }}" class="btn btn-success btn-block"><i class="fa fa-check-circle"></i> {{ __lang('complete-order') }}</a>

    </div>

@endsection
