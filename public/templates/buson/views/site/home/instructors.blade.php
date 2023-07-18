@extends(TLAYOUT)

@section('page-title',__('default.instructors'))
@section('inline-title',__('default.instructors'))

@section('content')
    <div class="completed-cases section-padding3">
        <div class="container">
            <div class="row">
                @foreach($admins as $admin)
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="single-cases-img  size mb-30">
                        <a href="{{ route('instructor',['admin'=>$admin->id]) }}">
                        @if(empty($admin->user->picture))
                        <img src="{{ asset('img/user.png') }}" alt="">
                    @else
                            <img src="{{ asset($admin->user->picture) }}" alt="">
                    @endif
                    </a>
                        <!-- img hover caption -->
                        <div class="single-cases-cap single-cases-cap2">
                            <h4><a  href="{{ route('instructor',['admin'=>$admin->id]) }}">{{ $admin->user->name.' '.$admin->user->last_name }}</a></h4>
                            <p>
                                @if(!empty($admin->social_facebook))
                                    <a  href="{{  $admin->social_facebook}}"><i class="fab fa-facebook-square social-btn"></i></a>
                                @endif

                                    @if(!empty($admin->social_twitter))
                                        <a  href="{{  $admin->social_twitter }}"><i class="fab fa-twitter-square social-btn"></i></a>
                                    @endif

                                    @if(!empty($admin->social_linkedin))
                                        <a  href="{{  $admin->social_linkedin }}"><i class="fab fa-linkedin social-btn"></i></a>
                                    @endif

                                    @if(!empty($admin->social_instagram))
                                        <a  href="{{  $admin->social_instagram }}"><i class="fab fa-instagram social-btn"></i></a>
                                    @endif

                                    @if(!empty($admin->social_website))
                                        <a  href="{{  $admin->social_website }}"><i class="fa fa-globe social-btn"></i></a>
                                    @endif

                            </p>
                        </div>
                    </div>
                </div>



                @endforeach

            </div>
        </div>
    </div>

@endsection
