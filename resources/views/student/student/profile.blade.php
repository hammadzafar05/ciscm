@extends('layouts.student')
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
    <form   enctype="multipart/form-data" method="post" action="{{ route('student.student.profile') }}">
        @csrf

        <div class="row">
            <div class="control-group col-md-6">
                {{  formLabel($form->get('name')) }}

                <div class="controls">
                    {{  formElement($form->get('name'))  }}
                    <p class="help-block">&nbsp;</p>
                </div>
            </div>

            <div class="control-group col-md-6">
                {{  formLabel($form->get('last_name')) }}

                <div class="controls">
                    {{  formElement($form->get('last_name'))  }}
                    <p class="help-block">&nbsp;</p>
                </div>
            </div>



            <div class="control-group col-md-6">
                {{  formLabel($form->get('mobile_number')) }}

                <div class="controls">
                    {{  formElement($form->get('mobile_number'))  }}
                    <p class="help-block">&nbsp;</p>
                </div>
            </div>

            <div class="control-group col-md-6">
                {{  formLabel($form->get('email')) }}

                <div class="controls">
                    {{  formElement($form->get('email'))  }}
                    <p class="help-block">{{  __lang('provide-email')  }}</p>
                </div>
            </div>


            <div class="control-group col-md-6">
                {{  formLabel($form->get('picture')) }}
                <div class="controls">


                    @php  if(!empty($row->picture) && isUrl($row->picture)): @endphp
                            <img src="{{ $row->picture }}" style="max-width: 200px" alt=""/>
                    <br> <br>
                    @php  elseif(!empty($row->picture) && isImage($row->picture)): @endphp
                      <img src="{{  resizeImage($row->picture,200,200,url('/')) }}" alt=""/>
                    <br> <br>
                    @php  endif;  @endphp

                    @php  if(!empty($row->picture)):  @endphp
                    <a class="btn btn-danger"  onclick="return confirm('{{ __lang('confirm-remove-picture') }}')" href="{{ route('student.student.removeimage') }}"><i class="fa fa-trash"></i> {{  __lang('Remove image')  }}</a>
                    <br> <br> @php  endif;  @endphp
                    {{  formElement($form->get('picture')) }} <p class="help-block">{{  formElementErrors($form->get('picture')) }}</p>
                </div>
            </div>
            @php  foreach($fields as $row): @endphp



            @php  if($row->type == 'checkbox'): @endphp
            <div class="control-group col-md-6">


                <div class="controls">
                    {{  formLabel($form->get('custom_'.$row->id)) }}  {{  formElement($form->get('custom_'.$row->id)) }} <p class="help-block">{{  formElementErrors($form->get('custom_'.$row->id)) }}</p>
                </div>
            </div>

            @php  elseif($row->type == 'radio'):  @endphp

            <div class="control-group col-md-6">
                {{  formLabel($form->get('custom_'.$row->id)) }}
                <div class="controls">
                    {{  $this->formRadio($form->get('custom_'.$row->id)) }} <p class="help-block">{{  formElementErrors($form->get('custom_'.$row->id)) }}</p>
                </div>
            </div>

            @php  elseif($row->type == 'file'):  @endphp


            <div class="control-group col-md-6">
                {{  formLabel($form->get('custom_'.$row->id)) }}
                <div class="controls">
                    @php  $valueRow = $table->getStudentFieldRecord($id,$row->id);  @endphp
                    @php  if(!empty($valueRow) && isImage($valueRow->value)): @endphp
                    <img src="{{  resizeImage($valueRow->value,200,200,url('/')) }}" alt=""/>

                    @php  endif;  @endphp
                    {{  formElement($form->get('custom_'.$row->id)) }} <p class="help-block">{{  formElementErrors($form->get('custom_'.$row->id)) }}</p>
                </div>
            </div>

            @php  else:  @endphp
            <div class="control-group col-md-6">
                {{  formLabel($form->get('custom_'.$row->id)) }}
                <div class="controls">
                    {{  formElement($form->get('custom_'.$row->id)) }} <p class="help-block">{{  formElementErrors($form->get('custom_'.$row->id)) }}</p>
                </div>
            </div>


            @php  endif;  @endphp




            @php  endforeach;  @endphp

        </div>



        <div class="form-footer"  >
            <button type="submit" class="btn btn-primary float-right">{{  __lang('Save Changes')  }}</button>
        </div>
    </form>


</div>
</div>




<!--container ends-->
<script src="{{  url('/') }}/client/vendor/intl-tel-input/build/js/intlTelInput.js"></script>

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
        utilsScript: "{{  url('/') }}/client/vendor/intl-tel-input/build/js/utils.js" // just for formatting/placeholders etc
    });
</script>
@endsection


@section('header')
    <link rel="stylesheet" href="{{ asset('client/vendor/intl-tel-input/build/css/intlTelInput.css') }}">
    <style>
        .iti-flag {background-image: url("{{  url('/') }}/client/vendor/intl-tel-input/build/img/flags.png");}

        @media only screen and (-webkit-min-device-pixel-ratio: 2), only screen and (min--moz-device-pixel-ratio: 2), only screen and (-o-min-device-pixel-ratio: 2 / 1), only screen and (min-device-pixel-ratio: 2), only screen and (min-resolution: 192dpi), only screen and (min-resolution: 2dppx) {
            .iti-flag {background-image: url("{{  url('/') }}/client/vendor/intl-tel-input/build/img/flags@2x.png");}
        }

    </style>
@endsection


@section('footer')

    <script type="text/javascript" src="{{ asset('client/vendor/intl-tel-input/build/js/utils.js') }}"></script>
    <script type="text/javascript" src="{{ asset('client/vendor/intl-tel-input/build/js/intlTelInput.js') }}"></script>


@endsection
