@extends('layouts.auth')
@section('page-title',__lang('register'))

@section('page-class')
    class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2"
@endsection

@section('content')

        <div class="card card-primary">
            <div class="card-header"><h4>{{ __lang('complete-registration') }}</h4></div>

            <div class="card-body">
                <form method="POST" action="{{ route('register.social') }}">
                    @csrf


                    <div class="row">
                        <div class="form-group col-12">
                            <label for="mobile_number">{{ __lang('telephone') }}</label>
                            <div>
                                <input id="mobile_number" type="text" class="form-control" name="mobile_number"  value="{{ old('mobile_number',$userClass->phone) }}" required >
                            </div>

                        </div>
                    </div>



                    <div class="row">

                        @foreach($fields as  $field)
                            @php
                                $value= old('field_'.$field->id);
                            @endphp
                            @if($field->type=='text')
                                <div class="form-group{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}  col-6">
                                    <label for="{{ 'field_'.$field->id }}">{{ $field->name }} @if(empty($field->required))(@lang('default.optional'))@endif</label>
                                    <input placeholder="{{ $field->placeholder }}" @if(!empty($field->required))required @endif  type="text" class="form-control" id="{{ 'field_'.$field->id }}" name="{{ 'field_'.$field->id }}" value="{{ $value }}">
                                    @if ($errors->has('field_'.$field->id))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('field_'.$field->id) }}</strong>
                                        </span>
                                    @endif
                                </div>
                            @elseif($field->type=='select')
                                <div class="form-group{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}  col-6">
                                    <label for="{{ 'field_'.$field->id }}">{{ $field->name }} @if(empty($field->required))(@lang('default.optional'))@endif</label>
                                    <?php
                                    $options = nl2br($field->options);
                                    $values = explode('<br />',$options);
                                    $selectOptions = [];
                                    foreach($values as $value2){
                                        $selectOptions[trim($value2)]=trim($value2);
                                    }
                                    ?>
                                    {{ Form::select('field_'.$field->id, $selectOptions,$value,['placeholder' => $field->placeholder,'class'=>'form-control']) }}
                                    @if ($errors->has('field_'.$field->id))
                                        <span class="help-block">
                                                                                        <strong>{{ $errors->first('field_'.$field->id) }}</strong>
                                                                                    </span>

                                    @endif
                                </div>
                            @elseif($field->type=='textarea')
                                <div class="form-group{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}  col-6">
                                    <label for="{{ 'field_'.$field->id }}">{{ $field->name }} @if(empty($field->required))(@lang('default.optional'))@endif</label>
                                    <textarea placeholder="{{ $field->placeholder }}" class="form-control" name="{{ 'field_'.$field->id }}" id="{{ 'field_'.$field->id }}" @if(!empty($field->required))required @endif  >{{ $value }}</textarea>
                                    @if ($errors->has('field_'.$field->id))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('field_'.$field->id) }}</strong>
                                        </span>
                                    @endif
                                </div>
                            @elseif($field->type=='checkbox')
                                <div class="checkbox  col-6">
                                    <label>
                                        <input name="{{ 'field_'.$field->id }}" type="checkbox" value="1" @if($value==1) checked @endif> {{ $field->name }}
                                    </label>
                                </div>

                            @elseif($field->type=='radio')
                                <?php
                                $options = nl2br($field->options);
                                $values = explode('<br />',$options);
                                $radioOptions = [];
                                foreach($values as $value3){
                                    $radioOptions[$value3]=trim($value3);
                                }
                                ?>
                                <h5><strong>{{ $field->name }}</strong></h5>
                                @foreach($radioOptions as $value2)
                                    <div class="radio  col-6">
                                        <label>
                                            <input type="radio" @if($value==$value2) checked @endif  name="{{ 'field_'.$field->id }}" id="{{ 'field_'.$field->id }}-{{ $value2 }}" value="{{ $value2 }}" >
                                            {{ $value2 }}
                                        </label>
                                    </div>
                                @endforeach
                            @elseif($field->type=='file')
                                <?php

                                $value='';
                                ?>


                                <div class="form-group{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}  col-6">
                                    <label for="{{ 'field_'.$field->id }}">{{ $field->name }} @if(empty($field->required))(@lang('default.optional'))@endif</label>
                                    <input placeholder="{{ $field->placeholder }}" @if(!empty($field->required))required @endif  type="file" class="form-control" id="{{ 'field_'.$field->id }}" name="{{ 'field_'.$field->id }}" >
                                    @if ($errors->has('field_'.$field->id))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('field_'.$field->id) }}</strong>
                                        </span>
                                    @endif
                                </div>

                            @endif

                        @endforeach

                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="agree" class="custom-control-input" id="agree" required {{ old('agree')? 'checked':'' }} >
                            <label class="custom-control-label" for="agree">{!!  __lang('i-accept-terms',['link'=>route('terms')]) !!}</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            {{ __lang('Register') }}
                        </button>
                    </div>
                    @if(setting('social_enable_facebook')==1 || setting('social_enable_google')==1)
                        <div class="text-center mt-4 mb-3">
                            <div class="text-job text-muted">{{ __lang('Or') }}</div>
                        </div>
                        <div class="row sm-gutters">
                            @if(setting('social_enable_facebook')==1)
                                <div class="col-6">
                                    <a href="{{ route('social.login',['network'=>'facebook']) }}" class="btn btn-block btn-social btn-facebook">
                                        <span class="fab fa-facebook"></span>{{ __lang('login-with') }} {{ __lang('facebook') }}
                                    </a>
                                </div>
                            @endif
                            @if(setting('social_enable_google')==1)
                                <div class="col-6">
                                    <a href="{{ route('social.login',['network'=>'google']) }}" class="btn btn-block btn-social btn-google">
                                        <span class="fab fa-google"></span>{{ __lang('login-with') }}  {{ __lang('google') }}
                                    </a>
                                </div>
                            @endif

                        </div>
                    @endif

                </form>
            </div>
        </div>

        <div class="mt-5 text-muted text-center">
            <a href="{{ route('login') }}">{{ __lang('already-have-account') }}</a>
        </div>



@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('client/themes/admin/assets/modules/jquery-selectric/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('client/vendor/intl-tel-input/build/css/intlTelInput.css') }}">
    <style>
        .iti-flag {background-image: url("{{ asset('client/vendor/intl-tel-input/build/img/flags.png') }}");}

        @media only screen and (-webkit-min-device-pixel-ratio: 2), only screen and (min--moz-device-pixel-ratio: 2), only screen and (-o-min-device-pixel-ratio: 2 / 1), only screen and (min-device-pixel-ratio: 2), only screen and (min-resolution: 192dpi), only screen and (min-resolution: 2dppx) {
            .iti-flag {background-image: url("{{ asset('client/vendor/intl-tel-input/build/img/flags@2x.png') }}");}
        }
    </style>
@endsection

@section('footer')
    <!-- JS Libraies -->
    <script src="{{ asset('client/themes/admin/assets/modules/jquery-pwstrength/jquery.pwstrength.min.js') }}"></script>
    <script src="{{ asset('client/themes/admin/assets/modules/jquery-selectric/jquery.selectric.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('client/themes/admin/assets/js/page/auth-register.js') }}"></script>

    <script src="{{ asset('client/vendor/intl-tel-input/build/js/intlTelInput.js') }}"></script>
    <script>
        var countryCode = 'BD';
        $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
            var countryCode = (resp && resp.country) ? resp.country : "";
        });
        $("input[name=mobile_number]").intlTelInput({
            initialCountry: countryCode,
            separateDialCode:true,
            hiddenInput:'fmobilenumber',
            geoIpLookup: function(callback) {
                $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "";
                    callback(countryCode);
                });
            },
            utilsScript: "{{ asset('client/vendor/intl-tel-input/build/js/utils.js') }}" // just for formatting/placeholders etc
        });
    </script>

@endsection
