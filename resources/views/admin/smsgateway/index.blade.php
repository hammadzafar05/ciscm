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

    <ul class="nav nav-pills" id="myTab3" role="tablist">
        <li class="nav-item">
            <a class="nav-link active"
               id="home-tab3"
               data-toggle="tab"
               href="#home3"
               role="tab"
               aria-controls="home"
               aria-selected="true">
                {{ __lang('gateways') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link"
               id="profile-tab3"
               data-toggle="tab"
               href="#profile3"
               role="tab"
               aria-controls="profile"
               aria-selected="false">
                {{ __lang('settings') }}
            </a>
        </li>
    </ul>
    <div class="tab-content"
         id="myTabContent2">
        <div class="tab-pane fade show active"
             id="home3"
             role="tabpanel"
             aria-labelledby="home-tab3">
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>{{ __lang('sms-gateway') }}</th>
                            <th>{{ __lang('url') }}</th>

                            <th>{{ __lang('installed') }}</th>
                            <th>{{ __lang('default') }}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($gateways as $smsGateway)
                            @php
                                $gateway = \App\SmsGateway::where('directory',$smsGateway)->first();
                            @endphp
                            <tr>
                                <td class="name">
                                    {{ __(smsInfo($smsGateway)['name']) }}
                                    <div><small>
                                            {{ smsInfo($smsGateway)['description'] }}
                                        </small></div>
                                </td>
                                <td>
                                    <a target="_blank" href="{{ smsInfo($smsGateway)['url'] }}">{{ smsInfo($smsGateway)['url'] }}</a>
                                </td>
                                <td>
                                    @if($gateway)
                                        {{ boolToString($gateway->enabled) }}
                                    @else
                                        {{ __lang('no') }}
                                    @endif
                                </td>
                                <td>
                                    @if($gateway)
                                        {{ boolToString($gateway->default) }}
                                    @else
                                        {{ __lang('no') }}
                                    @endif
                                </td>
                                <td>
                                    <div class="button-group dropleft">
                                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                            <i class="fa fa-cogs"></i> {{ __lang('actions') }}
                                        </button>
                                        <div class="dropdown-menu wide-btn">
                                            @if($gateway && $gateway->enabled==1)
                                                <a class="dropdown-item" href="{{ route('admin.smsgateway.edit',['smsGateway'=>$gateway->id]) }}"><i
                                                            class="fa fa-edit"></i> @lang('default.edit')</a>
                                                <a class="dropdown-item" href="{{ route('admin.smsgateway.uninstall',['smsGateway'=>$gateway->id]) }}"><i
                                                            class="fa fa-trash"></i> @lang('default.uninstall')</a>
                                            @else
                                                <a class="dropdown-item" href="{{ route('admin.smsgateway.install',['gateway'=>$smsGateway]) }}"><i
                                                            class="fa fa-download"></i> @lang('default.install')</a>
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
        </div>
        <div class="tab-pane fade" id="profile3" role="tabpanel" aria-labelledby="profile-tab3">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ selfURL() }}">
                        @csrf
                        <div class="form-group">
                            <label for="password1" class="control-label">{{ formLabel($form->get('sms_enabled')) }}</label>


                            <input type="checkbox" name="sms_enabled" value="1" @if($enabled==1) checked @endif>

                            <p class="help-block">{{ formElementErrors($form->get('sms_enabled')) }}</p>

                        </div>

                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary">{{ __lang('submit') }}</button>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>




@endsection
