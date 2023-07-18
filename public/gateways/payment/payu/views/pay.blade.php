@extends('layouts.checkout')
@section('page-title',$method->label)


@section('payment-content')


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
        <div id="msg"></div>
        <a  id="payu-confirm" href="#" class="btn btn-primary"><i class="fa fa-money"></i> {{ __lang('Pay Now') }} </a>
    </div>
@endsection
@section('footer')
    <script type="text/javascript"><!--
        $('#payu-confirm').click(function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('cart.method',['code'=>$code,'function'=>'traineasy_send']) }}?tid={{ $transaction->id }}',
                type: 'post',
                dataType: 'json',
                cache: false,
                beforeSend: function() {
                    $('#payu-confirm').hide();
                    $('#msg').html('<div class="attention">{{ __lang('contacting-payu')  }}</div>');
                },
                complete: function() {

                },
                success: function(json) {
                    if (json['redirect']) {
                        location.replace(json['redirect']);
                    } else {
                        $('#msg').text('');
                        $('#payu-confirm').show();
                        alert(json['error']);
                    }
                }
            });
        });
        //-->
    </script>

@endsection
