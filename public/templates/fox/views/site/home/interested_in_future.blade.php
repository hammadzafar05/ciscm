@extends(TLAYOUT)

@section('page-title',__('default.interested_in_future'))
@section('inline-title',__('default.interested_in_future'))
@section('content')

    <!-- ================ interested course section start ================= -->
    <section class="ftco-section">
        <div class="container px-4">

            <div class="row">
                <div class="col-12">
                    <h2 class="contact-title">@lang('default.interested_in_future') : {{ $course_name }}</h2>
                </div>
                <div class="col-lg-12">
                    <form class="form-contact contact_form_" action="{{ route('contact.send-mail') }}" method="post" >
                        @csrf
                        <div class="row">
                            <div class="col-12" style="display: none;">
                                <div class="form-group">
                                    <label for="message">Course Name</label>
                                    <input required
                                           class="form-control valid"
                                           name="message"
                                           id="message"
                                           type="text"
                                           value="{{ $course_name }}"
                                           onfocus="this.placeholder = ''" onblur="this.placeholder = '{{ $course_name }}'"
                                           placeholder="{{ $course_name }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">{{ addslashes(__t('enter-your-name')) }}</label>
                                    <input required
                                           class="form-control valid"
                                           name="name"
                                           id="name"
                                           type="text"
                                           onfocus="this.placeholder = ''"
                                           onblur="this.placeholder = '{{ addslashes(__t('enter-your-name')) }}'"
                                           placeholder="{{ __t('enter-your-name') }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">{{ addslashes(__t('enter-email')) }}</label>
                                    <input required
                                           class="form-control valid"
                                           name="email"
                                           id="email"
                                           type="email"
                                           onfocus="this.placeholder = ''"
                                           onblur="this.placeholder = '{{ addslashes(__t('enter-email')) }}'"
                                           placeholder="{{ __t('enter-email') }}">
                                </div>
                            </div>
                            <div class="col-12 display-hide">
                                <div class="form-group">
                                    <label for="name">{{ addslashes(__t('enter-subject')) }}</label>
                                    <input class="form-control"
                                           name="subject"
                                           id="subject"
                                           type="text"
                                           onfocus="this.placeholder = ''"
                                           onblur="this.placeholder = '{{ addslashes(__t('enter-subject')) }}'"
                                           placeholder="{{ __t('enter-subject') }}"
                                           value="@lang('default.interested_in_future') : {{ $course_name }}"
                                           readonly>
                                </div>
                            </div>

                            <div class="col-12">
                                <label>@lang('default.verification')</label><br/>
                                <label for="">{!! clean( captcha_img() ) !!}</label>
                                <input class="form-control" type="text" name="captcha" placeholder="@lang('default.verification-hint')"/>

                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary btn-lg btn-block">{{ __t('send') }}</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </section>
    <!-- ================ interested course section end ================= -->

@endsection
