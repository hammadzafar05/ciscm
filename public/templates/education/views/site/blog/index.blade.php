@extends(TLAYOUT)

@section('page-title',$title)
@section('inline-title',$title)
@section('content')


    <!--================Blog Area =================-->
    <section class="blog_area section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mb-5 mb-lg-0">
                    <div class="blog_left_sidebar">
                        @foreach($posts as $post)
                        <article class="blog_item">
                            @if(!empty($post->cover_photo))
                            <div class="blog_item_img">
                                @if(!empty($post->cover_photo))
                                <img class="card-img rounded-0" src="{{ asset($post->cover_photo) }}" alt="">
                                @endif
                                <a href="#" class="blog_item_date">
                                    <h3>{{ \Illuminate\Support\Carbon::parse($post->publish_date)->format('d') }}</h3>
                                    <p>{{ \Illuminate\Support\Carbon::parse($post->publish_date)->format('M') }}</p>
                                </a>
                            </div>
                            @endif

                            <div class="blog_details">
                                <a class="d-inline-block" href="{{ route('blog.post',['blogPost'=>$post->id,'slug'=>safeUrl($post->title)]) }}">
                                    <h2>{{ $post->title }}</h2>
                                </a>
                                <p>{{ limitLength(strip_tags($post->content),300) }}</p>

                                @if($post->admin)
                                <ul class="blog-info-link">
                                    <li><a href="#"><i class="fa fa-user"></i> {{ $post->admin->user->name }}</a></li>

                                </ul>
                                    @endif
                            </div>
                        </article>
                        @endforeach

                        <nav class="blog-pagination justify-content-center d-flex">
                            {!! clean( $posts->appends(['q' => Request::get('q'),'category' => Request::get('category')])->render() ) !!}

                        </nav>
                    </div>
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
                                    <a href="{{ route('blog') }}?category={{ $category->id }}" class="d-flex">
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
    <!--================Blog Area =================-->

@endsection
