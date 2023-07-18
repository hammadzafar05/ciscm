@extends(TLAYOUT)

@section('page-title',__('default.application_form'))
@section('inline-title',__('default.application_form'))
@section('content')

    <!-- ================ interested course section start ================= -->
    <section class="ftco-section">
        <div class="container px-4">

            <div class="row">
                {{--<div class="col-12">
                    <h2 class="contact-title">@lang('default.application_form')</h2>
                </div>--}}
                <div class="col-lg-12">
                    <form class="form-contact contact_form_" action="{{ route('application_form.create') }}" method="post" >
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="course_name">Course Name</label>
                                    <select id="course_name"
                                            class="form-control valid select2"
                                            name="course_name" required>
                                        <option value="">Select an option</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course }}" {{ (old('course_name') == $course) ? 'selected' : ''}}>{{ $course }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="form-group col-6">
                                <label for="frist_name">{{ __lang('first-name') }}</label>
                                <input id="frist_name" type="text" class="form-control" name="name" value="{{ old('name') }}"   autofocus="" required>
                            </div>
                            <div class="form-group col-6">
                                <label for="last_name">{{ __lang('last-name') }}</label>
                                <input id="last_name" type="text" class="form-control" name="last_name"  value="{{ old('last_name') }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-6">
                                <label for="email">{{ __lang('email') }}</label>
                                <input id="email" type="email" class="form-control" name="email"  value="{{ old('email') }}" required >
                                <div class="invalid-feedback">
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <label for="mobile_number">{{ __lang('telephone') }}</label>
                                <div>
                                    <input id="mobile_number"
                                           type="text"
                                           class="form-control"
                                           name="mobile_number"
                                           value="{{ old('mobile_number') }}"
                                           required >
                                    <input id="countryCode"
                                           type="hidden"
                                           name="countryCode">
                                </div>

                            </div>
                        </div>
                        <div class="row">

                            <div class="form-group  col-6">
                                <label for="field_3">Designation (Optional)</label>
                                <input placeholder="Example: Executive, Manager etc,"
                                       type="text"
                                       class="form-control"
                                       id="designation"
                                       name="designation"
                                       value="{{ old('designation') }}"
                                       autocomplete="off">
                            </div>

                            <div class="form-group  col-6">
                                <label for="field_4">Organization (Optional)</label>
                                <input placeholder="Example: Executive, Manager etc,"
                                       type="text"
                                       class="form-control"
                                       id="organization"
                                       name="organization"
                                       value="{{ old('Organization') }}"
                                       autocomplete="off">
                            </div>

                            <div class="form-group  col-6">
                                <label for="field_2">Country </label>
                                <select name="country"
                                        class="form-control"
                                        id="country" required>
                                    <option value="">Select an option</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->name }}" {{ (old('country') == $country->name) ? 'selected' : ''}}>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label>@lang('default.verification')</label><br/>
                                <label for="">{!! clean( captcha_img() ) !!}</label>
                                <input class="form-control" type="text" name="captcha" placeholder="@lang('default.verification-hint')"/>

                            </div>

                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary btn-lg btn-block">Submit</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </section>
    <!-- ================ interested course section end ================= -->

@endsection


@section('footer')
    <script src="{{ asset('client/vendor/select2/js/select2.min.js') }}"></script>
    <script>
        $('.select2').select2();
    </script>
@endsection


@section('header')
    <link rel="stylesheet" href="{{ asset('client/vendor/select2/css/select2.min.css') }}">
    <style type="text/css">
        .select2-container .select2-selection--single {
            height: 52px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 52px;
        }
    </style>
@endsection
