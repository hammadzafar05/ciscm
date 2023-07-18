@extends(TLAYOUT)

@section('page-title',$pageTitle)
@section('inline-title',$pageTitle)

@section('content')
    <section class="ftco-section">
        <div class="container-fluid px-4">
            <div class="row">

                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-8">
                        </div>
                        <div class="col-md-4 pull-right">
                            <div class="input-group">
                                <input type="text"
                                       id="search-criteria"
                                       class="form-control pl-3"
                                       name="search-criteria"
                                       placeholder="Search courses"
                                       onkeypress="handle(event)">
                                <span class="input-group-addon">
                                    <button type="button"
                                            placeholder=""
                                            class="form-control search_all">
                                        <span class="ion-ios-search"></span>
                                    </button>
                                </span>
                            </div>
                            <br>
                        </div>
                    </div>
                    <div class="row">

                        @if($paginator->count()==0)
                            {{ __lang('no-results') }}
                        @endif
                        @foreach($paginator as $course)
                            @php
                                $course = \App\Course::find($course->id);
                                $course_name = explode(' - Batch ',$course->name);
                                $course_name = @$course_name[0];
                                //dd($course_name);
                            @endphp

                            <div class="col-lg-6 col-md-12 search-results">
                                <div class="single-courses-item">
                                    <div class="row align-items-center">
                                        {{--<div class="col-lg-4 col-md-4">
                                            <div class="courses-image">
                                                <img src="{{ asset($course->picture) }}"
                                                     alt="image">
                                                <a class="link-btn"
                                                   href="{{ route('course',['course'=>$course->id,'slug'=>safeUrl($course->name)]) }}"></a>
                                            </div>
                                        </div>--}}
                                        <div class="col-lg-12 col-md-12">
                                            <div class="courses-content">
                                                {{--<span class="price">{{ sitePrice($course->fee) }}</span>--}}
                                                <h3>
                                                    <a href="{{ route('course',['course'=>$course->id,'slug'=>safeUrl($course->name)
                                                        ]) }}">{{ $course_name }}</a>
                                                </h3>
                                                <ul class="courses-content-footer d-flex justify-content-between align-items-center">
                                                    <li>Fee: {{ sitePrice($course->fee) }}</li>
                                                    <li><a href="{{ route('course',['course'=>$course->id,'slug'=>safeUrl($course->name)]) }}" class="btn btn-primary">{{ __lang('details') }}</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div>
                 
                    </div>
                </div>

            </div>
        </div> <!-- row -->
        </div>
    </section>
    <style>
        .single-courses-item {
            margin-bottom: 30px;
            position: relative;
            border-radius: 5px;
            background-color: #9e9e9e14;
            box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 2%);
        }
        .align-items-center {
            align-items: center!important;
        }
        .single-courses-item .courses-image {
            display: block;
            border-radius: 5px;
            position: relative;
            z-index: 1;
            overflow: hidden;
        }
        .single-courses-item .courses-image:before {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            border-radius: 5px;
            background-color: var(--blackColor);
            opacity: .2;
            z-index: 1;
            transition: var(--transition);
        }
        .single-courses-item .courses-image img {
            border-radius: 5px;
            width: 100%;
            transition: var(--transition);
        }
        .single-courses-item .courses-image .link-btn {
            position: absolute;
            left: 0;
            right: 0;
            border-radius: 5px;
            width: 100%;
            height: 100%;
            z-index: 2;
        }
        .single-courses-item .courses-content {
            border-radius: 5px;
            position: relative;
            padding: 25px;
        }
        .single-courses-item .courses-content .price {
            display: block;
            color: #fe4a55;
            margin-bottom: 3px;
            font-size: 28px;
            font-weight: 800;
        }
        .single-courses-item .courses-content h3 {
            margin-bottom: 12px;
            line-height: 1.3;
            font-size: 22px;
            font-weight: 800;
        }
        .single-courses-item .courses-content .courses-content-footer {
            list-style-type: none;
            padding-left: 0;
            padding-right: 30px;
            margin: 15px -7px 0;
        }
        .single-courses-item .courses-content .courses-content-footer li {
            color: #606060;
            font-size: 15px;
            position: relative;
            padding-left: 25px;
            padding-right: 7px;
        }
    </style>
@endsection
