@extends(studentLayout())
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
        <div class="card-body">
            <form     method="post" action="{{ route('student.student.save-billing') }}">
                @csrf
                <div class="form-group">
                    <label for="billing_firstname">{{ __lang('firstname') }}</label>
                    <input name="billing_firstname" value="{{ old('billing_firstname',$user->billing_firstname) }}" type="text" class="form-control">
                </div>
                <div class="form-group">
                    <label for="billing_lastname">{{ __lang('lastname') }}</label>
                    <input name="billing_lastname" value="{{ old('billing_lastname',$user->billing_lastname) }}" type="text" class="form-control">
                </div>
                <div class="form-group">
                    <label for="billing_country_id">{{ __('default.country') }}</label>

                    <select name="billing_country_id" id="billing_country_id" class="form-control select2">
                        <option value=""></option>
                        @foreach($countries as $country)
                            <option @if(old('billing_country_id',$user->billing_country_id)==$country->id) selected @endif value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="billing_state">{{ __('default.state') }}</label>
                    <input name="billing_state" value="{{ old('billing_state',$user->billing_state) }}" type="text" class="form-control">
                </div>
                <div class="form-group">
                    <label for="billing_city">{{ __('default.city') }}</label>
                    <input name="billing_city" value="{{ old('billing_city',$user->billing_city) }}" type="text" class="form-control">
                </div>
                <div class="form-group">
                    <label for="billing_address_1">{{ __('default.address-1') }}</label>
                    <input name="billing_address_1" value="{{ old('billing_address_1',$user->billing_address_1) }}" type="text" class="form-control">
                </div>
                <div class="form-group">
                    <label for="billing_address_2">{{ __('default.address-2') }}</label>
                    <input name="billing_address_2" value="{{ old('billing_address_2',$user->billing_address_2) }}" type="text" class="form-control">
                </div>

                <div class="form-group">
                    <label for="billing_zip">{{ __('default.zip')  }}</label>
                    <input name="billing_zip" value="{{ old('billing_zip',$user->billing_zip) }}" type="text" class="form-control">
                </div>
                <div class="form-footer"  >
                    <button type="submit" class="btn btn-primary float-right">{{  __lang('Save Changes')  }}</button>
                </div>
            </form>


        </div>
    </div>

@endsection




