@extends(TLAYOUT)

@section('page-title',__('default.contact-us'))
@section('inline-title',__('default.contact-us'))
@section('content')

    <!-- ================ contact section start ================= -->
    <section class="ftco-section">
        <div class="container px-4">

            <div class="row">
                <div class="col-12">
                    <h2 class="contact-title">@lang('default.get-in-touch-text')</h2>
                </div>
                <div class="col-lg-8">
                    <form class="form-contact contact_form_" action="{{ route('contact.send-mail') }}" method="post" >
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <textarea required class="form-control w-100" name="message" id="message" cols="30" rows="9" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{ addslashes(__t('enter-message')) }}'" placeholder=" {{ __t('enter-message') }}"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input required  class="form-control valid" name="name" id="name" type="text" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{ addslashes(__t('enter-your-name')) }}'" placeholder="{{ __t('enter-your-name') }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input required  class="form-control valid" name="email" id="email" type="email" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{ addslashes(__t('enter-email')) }}'" placeholder="{{ __t('enter-email') }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <input class="form-control" name="subject" id="subject" type="text" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{ addslashes(__t('enter-subject')) }}'" placeholder="{{ __t('enter-subject') }}">
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
                <div class="col-lg-3 offset-lg-1">

                    <div class="bg-light align-self-stretch box p-4 text-center mb-5">
                        <h3 class="mb-4"><i class="fa fa-home"></i> @lang('default.address')</h3>
                        <p>{!! clean(setting('general_address')) !!}</p>
                    </div>

                    <div class="bg-light align-self-stretch box p-4 text-center mb-5">
                        <h3 class="mb-4"><i class="fa fa-phone"></i> @lang('default.telephone')</h3>
                        <p>{{ setting('general_tel') }}</p>
                    </div>

                    <div class="bg-light align-self-stretch box p-4 text-center mb-5">
                        <h3 class="mb-4"><i class="fa fa-envelope"></i> @lang('default.email')</h3>
                        <p>{!! clean( setting('general_contact_email') ) !!}</p>
                    </div>



                </div>
            </div>
        </div>
    </section>
    <!-- ================ contact section end ================= -->













@endsection
