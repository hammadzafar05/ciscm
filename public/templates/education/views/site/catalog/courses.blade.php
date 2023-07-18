@extends(TLAYOUT)

@section('page-title',$pageTitle)
@section('inline-title',$pageTitle)

@section('content')

    <section class="about-area them-2 pb-130 pt-50 recent-area">
        <div class="container">
            <div class="row">
                <div class="col-md-3 mb-5">

                    @if($subCategories || $parent)
                    <ul class="list-group mb-5">
                        <li class="list-group-item active">{{ __lang('sub-categories') }}</li>
                        @if($parent)
                            <li class="list-group-item">
                                <a class="link" href="{{ route('courses') }}?group={{ $parent->id }}" ><strong>{{ __lang('parent') }}: {{ $parent->name }}</strong></a>
                            </li>
                            @endif

                       @if($subCategories)
                        @foreach($subCategories as $category)
                            <li class="list-group-item">
                                <a  class="link" href="{{ route('courses') }}?group={{ $category->id }}" >{{ $category->name }}</a>
                            </li>
                        @endforeach
                           @endif
                    </ul>
                    @endif

                    <ul class="list-group">
                        <li class="list-group-item active">{{ __lang('categories') }}</li>
                        <li class="list-group-item"><a class="link"  href="{{ route('courses') }}">{{ __lang('all-courses') }}</a></li>
                        @foreach($categories as $category)
                        <li class="list-group-item @if(request()->get('group') == $category->id) active @endif"><a  class="link"  href="{{ route('courses') }}?group={{ $category->id }}">{{ $category->name }}</a></li>
                        @endforeach

                    </ul>

                        <div class="card mt-3  " data-toggle="card-collapse" data-open="false">
                            <div class="card-header card-collapse-trigger">
                                 {{  __lang('Filter')  }}
                            </div>
                            <div class="card-body">
                                <form id="filterform" class="form" role="form"  method="get" action="{{  route('courses') }}">
                                    <div class="form-group input-group margin-none">
                                        <div class=" margin-none">
                                            <input type="hidden" name="group" value="{{  $group  }}"/>

                                            <div class="form-group">
                                                <label  for="filter">{{  __lang('search')  }}</label>
                                                {{  formElement($text)  }}
                                            </div>
                                            <div  class="form-group">
                                                <label  for="group">{{  __lang('sort')  }}</label>
                                              <div>{{  formElement($sortSelect)  }}</div>
                                            </div>

                                            <div   >
                                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> {{  __lang('filter')  }}</button>
                                                <button type="button" onclick="$('#filterform input, #filterform select').val(''); $('#filterform').submit();" class="btn btn-secondary">{{  __lang('clear')  }}</button>

                                            </div>

                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>


                </div>

                <div class="col-md-9">
                    @if($paginator->count()==0)
                        {{ __lang('no-results') }}
                    @endif

                    <div class="row">
                        @foreach($paginator as $course)
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <!-- Single course -->
                            <div class="single-course mb-70">
                                @if(!empty($course->picture))
                                <div class="course-img mb-2">
                                    <a href="{{ route('course',['course'=>$course->id,'slug'=>safeUrl($course->name)]) }}"><img class="course-img img-fluid" src="{{ asset($course->picture) }}" alt="{{ $course->name }}"></a>
                                </div>
                                @endif
                                <div class="course-caption">
                                    <div class="course-cap-top">
                                        <h4><a href="{{ route('course',['course'=>$course->id,'slug'=>safeUrl($course->name)]) }}">{{ $course->name}}</a></h4>
                                    </div>
                                    <div class="course-cap-mid d-flex justify-content-between">
                                        <p>{{ limitLength(strip_tags($course->short_description),50) }}</p>
                                    </div>
                                    <div class="course-cap-bottom d-flex justify-content-between">
                                        <ul>
                                            <li>{{ sitePrice($course->fee) }}</li>
                                        </ul>
                                        <span><a href="{{ route('course',['course'=>$course->id,'slug'=>safeUrl($course->name)]) }}" class="btn btn-primary float-right btn-sm"><i class="fa fa-info-circle"></i> {{ __lang('details') }}</a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>


                    <div>
                        @php
                            // add at the end of the file after the table
                                echo paginationControl(
                                // the paginator object
                                    $paginator,
                                    // the scrolling style
                                    'sliding',
                                    // the partial to use to render the control
                                    null,
                                    // the route to link to when a user clicks a control link
                                    route('courses')
                                );

                        @endphp
                    </div>
                </div>

                </div>
            </div> <!-- row -->
        </div>
    </section>




@endsection
