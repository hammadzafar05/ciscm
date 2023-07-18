@include('admin.partials.text-input',['name'=>'safe_key','label'=>__lang('safe-key')])
@include('admin.partials.text-input',['name'=>'api_username','label'=>__lang('api-username')])
@include('admin.partials.text-input',['name'=>'api_password','label'=>__lang('api-password')])
@include('admin.partials.select',['name'=>'transaction_mode','label'=>__lang('transaction-mode'),'options'=>['staging'=>__lang('staging'),'production'=>__lang('production')]])
@include('admin.partials.select',['name'=>'transaction_type','label'=>__lang('transaction-type'),'options'=>['PAYMENT'=>__lang('payment'),'RESERVE'=>__lang('reserve')]])
@include('admin.partials.select',['name'=>'billing_currency','label'=>__lang('billing-currency'),'options'=>[
    'ARS'=>'Argentina Peso (ARS)',
    'BRL'=>'Brazil Real (BRL)',
    'CLP'=>'Chile Peso (CLP)',
    'COP'=>'Colombia Peso (COP)',
    'CZK'=>'Czech Republic Koruna (CZK)',
    'HUF'=>'Hungary Forint (HUF)',
    'INR'=>'India Rupee (INR)',
    'MXN'=>'Mexico Peso (MXN)',
    'NGN'=>'Nigeria Naira (NGN)',
    'PAB'=>'Panama Balboa (PAB)',
    'PEN'=>'Peru Sol (PEN)',
    'PLN'=>'Poland Zloty (PLN)',
    'RON'=>'Romania Leu (RON)',
    'RUB'=>'Russia Ruble (RUB)',
    'ZAR'=>'South Africa Rand (ZAR)',
    'TRY'=>'Turkey Lira (TRY)'

]])
@include('admin.partials.select-multiple',['name'=>'payment_methods','label'=>__lang('payment-methods'),'options'=>[
    'payu_easyplus_method_credit_card'=>__lang('credit-card'),
    'payu_easyplus_method_discovery_miles'=>'Discovery Miles',
    'payu_easyplus_method_ebucks'=>'Ebucks',
    'payu_easyplus_method_eft'=>'EFT',
    'payu_easyplus_method_masterpass'=>'Masterpass',
    'payu_easyplus_method_rcs'=>'RCS',
    'payu_easyplus_method_eft_pro'=>'EFT Pro',
    'payu_easyplus_method_creditcard_vco'=>__lang('credit-card').' VCO',
    'payu_easyplus_method_mobicred'=>'Mobicred',
]])
@include('admin.partials.select',['name'=>'debug','label'=>__lang('debug'),'options'=>['1'=>__lang('yes'),'0'=>__lang('no')]])

