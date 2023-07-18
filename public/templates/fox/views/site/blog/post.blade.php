@extends(TLAYOUT)

@section('page-title',$blogPost->title)
@section('inline-title',$blogPost->title)
@section('crumb')
    <span><a href="@route('blog')">@lang('default.blog') <i class="ion-ios-arrow-forward"></i></a></span>

    <span class="mr-2"><a href="#">@lang('default.blog-post')</a></span>
@endsection
@section('content')

    <section class="ftco-section bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <div class="row">

                          <div class="col-md-12 col-lg-12 ftco-animate">
                                <div class="blog-entry">
                                    <a href="{{ route('blog.post',['blogPost'=>$blogPost->id]) }}" class="block-20 d-flex align-items-end" @if(!empty($blogPost->cover_photo)) style="background-image: url('{{ asset($blogPost->cover_photo) }}');" @endif >
                                        <div class="meta-date text-center p-2">
                                            <span class="day">{{  \Carbon\Carbon::parse($blogPost->publish_date)->format('D') }}</span>
                                            <span class="mos">{{  \Carbon\Carbon::parse($blogPost->publish_date)->format('M') }}</span>
                                            <span class="yr">{{  \Carbon\Carbon::parse($blogPost->publish_date)->format('Y') }}</span>
                                        </div>
                                    </a>
                                    <div class="text bg-white p-4">
                                        <h3 class="heading"><a href="{{ route('blog.post',['blogPost'=>$blogPost->id]) }}">{{ $blogPost->title }}</a></h3>
                                        <p>{!! $blogPost->content !!} </p>
                                        <div class="d-flex align-items-center mt-4">

                                            <p class="ml-auto mb-0">
                                                @if($blogPost->admin)
                                                    <small>  <a @if($blogPost->admin->public == 1)  href="{{ route('instructor',['admin'=>$blogPost->admin_id]) }}" @endif class="mr-2"><i class="fa fa-user"></i> {{ $blogPost->admin->user->name.' '.$blogPost->admin->user->last_name }}</a>
                                                    </small>   @endif
                                            </p>
                                        </div>
                                        @if(!empty(setting('general_disqus_shortcode')))
                                            <div class="comments-area">
                                                <script  type="text/javascript">
                                                    "use strict";
                                                    /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
                                                    var disqus_shortname = '{{ setting('general_disqus_shortcode') }}'; // required: replace example with your forum shortname

                                                    /* * * DON'T EDIT BELOW THIS LINE * * */
                                                    (function () {
                                                        var s = document.createElement('script'); s.async = true;
                                                        s.type = 'text/javascript';
                                                        s.src = '//' + disqus_shortname + '.disqus.com/count.js';
                                                        (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
                                                    }());
                                                </script>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>




                    </div>


                </div>
                <div class="col-md-3">
                    <aside class="single_sidebar_widget search_widget">
                        <form method="get" action="{{ route('blog') }}">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <input  name="q"  type="text" class="form-control" placeholder='@lang('default.search')'
                                            onfocus="this.placeholder = ''"
                                            onblur="this.placeholder = '@lang('default.search')'">

                                </div>
                            </div>
                        </form>
                    </aside>

                    <aside class="mt-4">

                        <ul class="list-group">
                            <li class="list-group-item active">{{ __lang('categories') }}</li>
                            @foreach($categories as $category)
                                <li class="list-group-item @if(request()->get('category') == $category->id) active @endif"><a href="{{ route('blog') }}?category={{ $category->id }}">{{ $category->name }}</a></li>
                            @endforeach

                        </ul>


                    </aside>

                    <aside class="mt-4">

                        <div class="ftco-footer-widget mb-5">
                            <h4 class="ftco-heading-2">{{ __t('recent-posts') }}</h4>
                            @foreach(\App\BlogPost::whereDate('publish_date','<=',\Illuminate\Support\Carbon::now()->toDateTimeString())->where('enabled',1)->orderBy('publish_date','desc')->limit(5)->get() as $blogPost)

                                <div class="block-21 mb-4 d-flex">
                                    @if(!empty($blogPost->cover_photo))
                                        <a class="blog-img mr-4" style="background-image: url({{ asset($blogPost->cover_photo) }});"></a>
                                    @endif
                                    <div class="text">
                                        <h3 class="heading"><a href="{{ route('blog.post',['blogPost'=>$blogPost->id]) }}">{{ $blogPost->title }}</a></h3>
                                        <div class="meta">
                                            <div><a href="#"><span class="icon-calendar"></span> {{ \Carbon\Carbon::parse($blogPost->publish_date)->format('M d, Y') }}</a></div>
                                            @if($blogPost->admin)
                                                <div><a @if($blogPost->admin->public == 1)  href="{{ route('instructor',['admin'=>$blogPost->admin_id]) }}" @endif ><span class="icon-person"></span> {{ $blogPost->admin->user->name.' '.$blogPost->admin->user->last_name }}</a></div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>


                    </aside>

                </div>
            </div>

        </div>
    </section>


    @if(false)
    <!--================Blog Area =================-->
    <section class="blog_area single-post-area section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 posts-list">
                    <div class="single-post">
                        @if(!empty($blogPost->cover_photo))
                        <div class="feature-img">
                            <img class="img-fluid" src="{{ asset($blogPost->cover_photo) }}" alt="">
                        </div>
                        @endif
                        <div class="blog_details">
                            <h2>{{ $blogPost->title }}
                            </h2>
                            <ul class="blog-info-link mt-3 mb-4">
                                @if($blogPost->admin->user()->exists())
                                <li><a href="#"><i class="fa fa-user"></i>{{ $blogPost->admin->user->name }}</a></li>
                                @endif
                             </ul>
                            <p>{!! clean( $blogPost->content ) !!}</p>
                        </div>
                    </div>

                    @if(!empty(setting('general_disqus_shortcode')))
                        <div class="comments-area">
                            <script  type="text/javascript">
"use strict";
                                /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
                                var disqus_shortname = '{{ setting('general_disqus_shortcode') }}'; // required: replace example with your forum shortname

                                /* * * DON'T EDIT BELOW THIS LINE * * */
                                (function () {
                                    var s = document.createElement('script'); s.async = true;
                                    s.type = 'text/javascript';
                                    s.src = '//' + disqus_shortname + '.disqus.com/count.js';
                                    (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
                                }());
                            </script>
                        </div>
                    @endif
                </div>
                <div class="col-lg-4">
                    <div class="blog_right_sidebar">
                        <aside class="single_sidebar_widget search_widget">
                            <form method="get" action="{{ route('blog') }}">
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <input  name="q"  type="text" class="form-control" placeholder='@lang('default.search')'
                                                onfocus="this.placeholder = ''"
                                                onblur="this.placeholder = '@lang('default.search')'">
                                        <div class="input-group-append">
                                            <button class="btns" type="submit"><i class="ti-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <button class="button rounded-0 primary-bg text-white w-100 btn_1 boxed-btn"
                                        type="submit">@lang('default.search')</button>
                            </form>
                        </aside>

                        <aside class="single_sidebar_widget post_category_widget">
                            <h4 class="widget_title">@lang('default.category')</h4>
                            <ul class="list cat-list">
                                @foreach($categories as $category)
                                    <li>
                                        <a href="#" class="d-flex">
                                            <p>{{ $category->name }}</p>
                                            <p>&nbsp;({{ $category->blogPosts()->count() }})</p>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </aside>

                        <aside class="single_sidebar_widget popular_post_widget">
                            <h3 class="widget_title">{{ __t('recent-posts') }}</h3>
                            @foreach(\App\BlogPost::whereDate('publish_date','<=',\Illuminate\Support\Carbon::now()->toDateTimeString())->where('enabled',1)->orderBy('publish_date','desc')->limit(5)->get() as $blogPost)
                                <div class="media post_item">
                                    @if(!empty($blogPost->cover_photo))
                                        <img class="recent-blog-img" src="{{ asset($blogPost->cover_photo) }}" alt="">
                                    @endif
                                    <div class="media-body">
                                        <a href="{{ route('blog.post',['blogPost'=>$blogPost->id]) }}">
                                            <h3>{{ $blogPost->title }}</h3>
                                        </a>
                                        <p>{{  \Carbon\Carbon::parse($blogPost->publish_date)->format('F d, Y') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </aside>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================ Blog Area end =================-->
@endif

@endsection
