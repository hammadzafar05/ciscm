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
                                <a href="{{ route('courses') }}?group={{ $parent->id }}" ><strong>{{ __lang('parent') }}: {{ $parent->name }}</strong></a>
                            </li>
                            @endif

                       @if($subCategories)
                        @foreach($subCategories as $category)
                            <li class="list-group-item">
                                <a href="{{ route('courses') }}?group={{ $category->id }}" >{{ $category->name }}</a>
                            </li>
                        @endforeach
                           @endif
                    </ul>
                    @endif

                    <ul class="list-group">
                        <li class="list-group-item active">{{ __lang('categories') }}</li>
                        <li class="list-group-item"><a href="{{ route('courses') }}">{{ __lang('all-courses') }}</a></li>
                        @foreach($categories as $category)
                        <li class="list-group-item @if(request()->get('group') == $category->id) active @endif"><a href="{{ route('courses') }}?group={{ $category->id }}">{{ $category->name }}</a></li>
                        @endforeach

                    </ul>

                        <div class="card mt-3  " data-toggle="card-collapse" data-open="false">
                            <div class="card-header card-collapse-trigger">
                                 {{  __lang('Filter')  }}
                            </div>
                            <div class="card-body">
                                <form id="filterform" class="form" role="form"  method="get" action="{{  route('courses') }}">
                                    <div class="form-group input-group margin-none">
                                        <div class="margin-none">
                                            <input type="hidden" name="group" value="{{  $group  }}"/>

                                            <div class="form-group">
                                                <label  for="filter">{{  __lang('search')  }}</label>
                                                {{  formElement($text)  }}
                                            </div>
                                            <div  class="form-group">
                                                <label  for="group">{{  __lang('sort')  }}</label>
                                                {{  formElement($sortSelect)  }}
                                            </div>

                                            <div >
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
                    <div class="row">
                        @if($paginator->count()==0)
                            {{ __lang('no-results') }}
                            @endif
                        @foreach($paginator as $course)
                            <div class="col-xl-4 col-lg-4 col-md-6">
                                <div class="single-recent-cap mb-30 ">
                                    <div class="recent-img text-center" style="max-height: 300px">
                                        @if(!empty($course->picture))
                                            <a href="{{ route('course',['course'=>$course->id,'slug'=>safeUrl($course->name)]) }}"><img class="course-img" src="{{ asset($course->picture) }}" alt="{{ $course->name }}"></a>
                                        @endif

                                    </div>
                                    <div class="recent-cap pb-5">
                                        <span>{{ sitePrice($course->fee) }}</span>
                                        <h4><a href="{{ route('course',['course'=>$course->id,'slug'=>safeUrl($course->name)]) }}">{{ $course->name}}</a></h4>
                                        <p>{{ limitLength(strip_tags($course->short_description),50) }}</p>
                                        <a href="{{ route('course',['course'=>$course->id,'slug'=>safeUrl($course->name)]) }}" class="btn btn-primary float-right btn-sm"><i class="fa fa-info-circle"></i> {{ __lang('details') }}</a>
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
