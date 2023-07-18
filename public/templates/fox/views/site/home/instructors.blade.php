@extends(TLAYOUT)

@section('page-title',__('default.instructors'))
@section('inline-title',__('default.instructors'))

@section('content')
    <section class="ftco-section">
        <div class="container px-4">
            <div class="row">
                @foreach($admins as $admin)
                    <div class="col-md-6 col-lg-3 ftco-animate">
                        <div class="staff">
                            <div class="img-wrap d-flex align-items-stretch">
                                @if(empty($admin->user->picture))
                                    <div class="img align-self-stretch" style="background-image: url({{ asset('img/user.png') }});"></div>

                                @else
                                    <div class="img align-self-stretch" style="background-image: url({{ asset($admin->user->picture) }});"></div>

                                @endif

                            </div>
                            <div class="text pt-3 text-center">
                                <h3><a  href="{{ route('instructor',['admin'=>$admin->id]) }}">{{ $admin->user->name.' '.$admin->user->last_name }}</a></h3>

                                <div class="faded">
                                    <p>{{ limitLength($admin->about,100) }}</p>
                                    <ul class="ftco-social text-center">
                                        @if(!empty($admin->social_facebook))
                                            <li class="ftco-animate"><a href="{{  $admin->social_facebook}}"><span class="icon-facebook"></span></a></li>
                                        @endif

                                        @if(!empty($admin->social_twitter))
                                            <li class="ftco-animate"><a href="{{  $admin->social_twitter }}"><span class="icon-twitter"></span></a></li>
                                        @endif

                                        @if(!empty($admin->social_linkedin))
                                            <li class="ftco-animate"><a href="{{  $admin->social_linkedin }}"><span class="icon-linkedin"></span></a></li>
                                        @endif

                                        @if(!empty($admin->social_instagram))
                                            <li class="ftco-animate"><a href="{{  $admin->social_instagram }}"><span class="icon-instagram"></span></a></li>
                                        @endif

                                        @if(!empty($admin->social_website))
                                            <li class="ftco-animate"><a href="{{  $admin->social_website }}"><span class="icon-globe"></span></a></li>
                                        @endif

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach

            </div>
        </div>
    </section>

@endsection
