@extends(adminLayout())
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('content')
<div class="card">
<div class="card-body">
  <div class="alert alert-info">
      {{__lang('currencies-help')}}
      <a href="{{ adminUrl(['controller'=>'setting','action'=>'currencies']) }}" style="text-decoration: underline" target="_blank">{{__lang('currency-setup')}}</a> {{strtolower(__lang('page'))}}

  </div>
    <form id="currencyform" class="form" method="post" action="{{ selfURL() }}">
        @csrf
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group" >
                    <label for="currency">{{__lang('select-currency')}}</label>
                    <select class="form-control select2" required="required" name="currency" id="currency">
                        <option value=""></option>
                        @foreach($currencies as $currency)
                            <option value="{{ $currency->id }}">{{ $currency->country->currency_name }} - {{ $currency->country->currency_code }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> {{__lang('add-currency')}}</button>
            </div>
        </div>


    </form>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>{{__lang('currency')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($rowset as $row)
            <tr>
                <td>{{ $row->country->currency_name }} - {{ $row->country->currency_code }}</td>
                <td><a class="btn btn-primary delete" href="{{ adminUrl(['controller'=>'payment','action'=>'deletecurrency','paymentMethod'=>$paymentMethod->id,'id'=>$row->id]) }}"><i class="fa fa-trash"></i> {{__lang('remove')}}</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
</div>

@endsection
