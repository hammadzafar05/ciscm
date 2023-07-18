@extends(TLAYOUT)

@section('page-title',$title)
@section('inline-title',$title)
@section('content')

    <section class="ftco-section bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <div class="row">

                        @foreach($posts as $post)
                            <div class="col-md-6 col-lg-6 ftco-animate">
                                <div class="blog-entry">

                                    @if(!empty($post->cover_photo))
                                        @php
                                            $image = $post->cover_photo;
                                            if (file_exists(public_path($image))) {
                                                $image = $post->cover_photo;
                                            } else {
                                                $image = 'img/ward.jpg';
                                            }
                                        @endphp
                                    @else
                                        @php $image = 'img/ward.jpg'; @endphp
                                    @endif

                                    <a href="{{ route('blog.post',['blogPost'=>$post->id,'slug'=>safeUrl($post->title)]) }}"
                                       class="block-20 d-flex align-items-end"
                                       style="background-image: url('{{ asset($image) }}');">
                                        <div class="meta-date text-center p-2">
                                            <span class="day">{{  \Carbon\Carbon::parse($post->publish_date)->format('D') }}</span>
                                            <span class="mos">{{  \Carbon\Carbon::parse($post->publish_date)->format('M') }}</span>
                                            <span class="yr">{{  \Carbon\Carbon::parse($post->publish_date)->format('Y') }}</span>
                                        </div>
                                    </a>
                                    <div class="text bg-white p-4">
                                        <h3 class="heading"><a href="{{ route('blog.post',['blogPost'=>$post->id,'slug'=>safeUrl($post->title)]) }}">{{ $post->title }}</a></h3>
                                        <p>{{ limitLength(strip_tags($post->content),100) }}</p>
                                        <div class="d-flex align-items-center mt-4">
                                            <p class="mb-0"><a href="{{ route('blog.post',['blogPost'=>$post->id,'slug'=>safeUrl($post->title)]) }}" class="btn btn-primary">{{ __lang('read-more') }} <span class="ion-ios-arrow-round-forward"></span></a></p>
                                            <p class="ml-auto mb-0">
                                                @if($post->admin)
                                                 <small>  <a @if($post->admin->public == 1)  href="{{ route('instructor',['admin'=>$post->admin_id]) }}" @endif class="mr-2"><i class="fa fa-user"></i> {{ $post->admin->user->name.' '.$post->admin->user->last_name }}</a>
                                                    </small>   @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach



                    </div>

                    <nav class="blog-pagination justify-content-center d-flex">
                        {!! clean( $posts->appends(['q' => Request::get('q'),'category' => Request::get('category')])->render() ) !!}

                    </nav>
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
                            @foreach(\App\BlogPost::whereDate('publish_date','<=',\Illuminate\Support\Carbon::now()->toDateTimeString())->where('enabled',1)->orderBy('publish_date','desc')->limit(5)->get() as $post)

                                <div class="block-21 mb-4 d-flex">
                                    @if(!empty($post->cover_photo))
                                        <a class="blog-img mr-4" style="background-image: url({{ asset($post->cover_photo) }});"></a>
                                    @endif
                                    <div class="text">
                                        <h3 class="heading"><a href="{{ route('blog.post',['blogPost'=>$post->id,'slug'=>safeUrl($post->title)]) }}">{{ $post->title }}</a></h3>
                                        <div class="meta">
                                            <div><a href="#"><span class="icon-calendar"></span> {{ \Carbon\Carbon::parse($post->publish_date)->format('M d, Y') }}</a></div>
                                            @if($post->admin)
                                                <div><a @if($post->admin->public == 1)  href="{{ route('instructor',['admin'=>$post->admin_id]) }}" @endif ><span class="icon-person"></span> {{ $post->admin->user->name.' '.$post->admin->user->last_name }}</a></div>
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



@endsection
