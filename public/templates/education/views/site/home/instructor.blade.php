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
                    <div class="col-md-4 team-area ">
                        <div class="single-team mb-30">
                            <div class="team-img">
                                <a href="{{ route('instructor',['admin'=>$admin->id]) }}">
                                    @if(empty($admin->user->picture))
                                        <img src="{{ asset('img/user.png') }}" alt="">
                                    @else
                                        <img src="{{ asset($admin->user->picture) }}" alt="">
                                    @endif
                                </a>
                                <!-- Blog Social -->
                                <ul class="team-social">
                                    @if(!empty($admin->social_facebook))
                                        <li><a  href="{{  $admin->social_facebook}}"><i class="fab fa-facebook-f"></i></a></li>
                                    @endif

                                    @if(!empty($admin->social_twitter))
                                        <li><a  href="{{  $admin->social_twitter }}"><i class="fab fa-twitter"></i></a></li>
                                    @endif

                                    @if(!empty($admin->social_linkedin))
                                        <li><a  href="{{  $admin->social_linkedin }}"><i class="fab fa-linkedin"></i></a></li>
                                    @endif

                                    @if(!empty($admin->social_instagram))
                                        <li><a  href="{{  $admin->social_instagram }}"><i class="fab fa-instagram"></i></a></li>
                                    @endif

                                    @if(!empty($admin->social_website))
                                        <li><a  href="{{  $admin->social_website }}"><i class="fas fa-globe"></i></a></li>
                                    @endif
                                </ul>
                            </div>
                            <div class="team-caption">
                                <h3><a  href="{{ route('instructor',['admin'=>$admin->id]) }}">{{ $admin->user->name.' '.$admin->user->last_name }}</a></h3>

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
