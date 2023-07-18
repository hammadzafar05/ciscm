@extends(TLAYOUT)

@section('page-title',$blogPost->title)
@section('inline-title',$blogPost->title)
@section('crumb')
    <span><a href="@route('blog')">@lang('default.blog')</a></span>
    <span>/</span>
    <span>@lang('default.blog-post')</span>
@endsection
@section('content')
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
                            <p>{!! $blogPost->content !!}</p>
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
                            @foreach(\App\BlogPost::whereDate('publish_date','<=',\Illuminate\Support\Carbon::now()->toDateTimeString())->where('enabled',1)->orderBy('publish_date','desc')->limit(5)->get() as $post)
                                <div class="media post_item">
                                    @if(!empty($post->cover_photo))
                                        <img class="recent-blog-img" src="{{ asset($post->cover_photo) }}" alt="">
                                    @endif
                                    <div class="media-body">
                                        <a href="{{ route('blog.post',['blogPost'=>$post->id,'slug'=>safeUrl($post->title)]) }}">
                                            <h3>{{ $post->title }}</h3>
                                        </a>
                                        <p>{{  \Carbon\Carbon::parse($post->publish_date)->format('F d, Y') }}</p>
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


@endsection
