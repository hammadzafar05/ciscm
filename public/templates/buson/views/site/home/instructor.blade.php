@extends(TLAYOUT)

@section('page-title',$admin->user->name.' '.$admin->user->last_name)
@section('inline-title',$admin->user->name.' '.$admin->user->last_name)
@section('crumb')
    <span><a href="@route('instructors')">@lang('default.instructors')</a></span>
    <span>/</span>
    <span>@lang('default.details')</span>
@endsection
@section('content')
    <div class="completed-cases section-padding3">
        <div class="container">
            <div class="row">
                    <div class="col-md-4">
                        <div class="single-cases-img  size mb-30">
                            <a    href="#">
                                @if(empty($admin->user->picture))
                                    <img src="{{ asset('img/user.png') }}" alt="">
                                @else
                                    <img src="{{ asset($admin->user->picture) }}" alt="">
                                @endif
                            </a>
                            <!-- img hover caption -->
                            <div class="single-cases-cap single-cases-cap2">
                                <h4><a href="#">{{ $admin->user->name.' '.$admin->user->last_name }}</a></h4>
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
                    <div class="col-md-8">
                        <div class="card">
                         <div class="card-header">
                              {{ __lang('about') }}
                        </div>
                        <div class="card-body">
                            {!! clean($admin->about) !!}
                        </div>
                        </div>
                    </div>



            </div>
        </div>
    </div>

@endsection
