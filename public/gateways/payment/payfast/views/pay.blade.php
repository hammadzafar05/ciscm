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
        <?php if ($sandbox) { ?>
        <div class="warning" style="color: red"><?= __lang('payfast-warning') ?> </div>
        <?php } ?>
        <form action="<?php echo $action; ?>" method="post">
            <input type="hidden" name="merchant_id" value="<?php echo $merchant_id; ?>" />
            <input type="hidden" name="merchant_key" value="<?php echo $merchant_key; ?>" />
            <input type="hidden" name="amount" value="<?php echo $amount; ?>" />
            <input type="hidden" name="item_name" value="<?php echo $item_name; ?>" />
            <input type="hidden" name="item_description" value="<?php echo $item_description; ?>" />
            <input type="hidden" name="name_first" value="<?php echo $name_first; ?>" />
            <input type="hidden" name="name_last" value="<?php echo $name_last; ?>" />
            <input type="hidden" name="email_address" value="<?php echo $email_address; ?>" />
            <input type="hidden" name="return_url" value="<?php echo $return_url; ?>" />
            <input type="hidden" name="notify_url" value="<?php echo $notify_url; ?>" />
            <input type="hidden" name="cancel_url" value="<?php echo $cancel_url; ?>" />
            <input type="hidden" name="custom_str1" value="<?php echo $custom_str1; ?>" />
            <input type="hidden" name="m_payment_id" value="<?php echo $m_payment_id; ?>" />
            <input type="hidden" name="signature" value="<?php echo $signature; ?>" />
            <div class="buttons">
                <div class="right">
                    <input type="submit" value="<?= __lang('pay-now') ?>" class="btn btn-primary" />
                </div>
            </div>
        </form>

    </div>

@endsection
