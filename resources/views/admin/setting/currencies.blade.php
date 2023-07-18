@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-md-10">
    {!! clean(__lang('currency-help',['url'=>adminUrl(['controller'=>'setting','action'=>'index'])])) !!}}
</div>
    <div class="col-md-2">
        <a id="currencyBtn" class="btn btn-primary float-right" href="#" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> {{__lang('add-currency')}}</a>
     </div>

</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>{{__lang('currency')}}</th>
            <th style="width: 150px">{{__lang('exchange-rate')}}</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($currencies as $currency)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $currency->country->currency_name }} - {{ $currency->country->currency_code }} @if($currentCountry == $currency->country_id) <strong>({{__lang('default')}})</strong>  @endif</td>
                <td >
                    <p class="age{{ $currency->id }}">

  <span class="editable{{ $currency->id }}">
    {{ $currency->exchange_rate }}
  </span>
                        <input type="button" value="{{__lang('edit')}}" class="btn btn-primary float-right btn-sm btn_edit{{ $currency->id }}"></input>
                    </p>
                </td>
                <td>
                    @if($currentCountry != $currency->country_id)
                    <a onclick="return confirm('{{__lang('currency-remove-confirm')}}')" class="btn btn-danger" href="{{ adminUrl(['controller'=>'setting','action'=>'deletecurrency','id'=>$currency->id]) }}"><i class="fa fa-trash"></i> {{__lang('remove')}}</a>
                    @endif
                </td>
            </tr>
            @section('footer')
                @parent
            <script>
                // edit button
                var option = {trigger : $(".age{{ $currency->id }} .btn_edit{{ $currency->id }}"), action : "click"};
                $(".age{{ $currency->id }} .editable{{ $currency->id }}").editable(option, function(e){
                    if(isNaN(e.value)){
                        $(".age{{ $currency->id }} .editable{{ $currency->id }}").html(e.old_value);
                        alert(e.value + " is not a valid rate");
                    }
                    else{
                        $.post('{{ adminUrl(['controller'=>'setting','action'=>'updatecurrency','id'=>$currency->id]) }}',{'rate': e.value,'_token':'{{ csrf_token() }}'});
                    }
                });


            </script>
            @endsection
            @endforeach
    </tbody>
</table>
{{ $currencies->links() }}

@endsection

@section('footer')
    <script type="text/javascript" src="{{ asset('client/vendor/jquery.editable/jquery.editable.min.js') }}"></script>
    <!-- Modal -->
    <div class="modal fade" id="myModal"  role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form" method="post" action="{{ adminUrl(['controller'=>'setting','action'=>'addcurrency']) }}">
              @csrf      <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">{{__lang('add-currency')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                    </div>
                    <div class="modal-body">


                        <div class="form-group">
                            <label for="country">{{__lang('currency')}}</label>
                            <select class="form-control select2" name="country" id="country">
                                <option value=""></option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->currency_name }} ({{ $country->currency_code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exchange_rate">{{__lang('exchange-rate')}}</label>
                            <input class="form-control digit" type="text" value="" name="exchange_rate"/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__lang('close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__lang('add-currency')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
