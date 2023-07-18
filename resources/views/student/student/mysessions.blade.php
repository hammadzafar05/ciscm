@extends('layouts.student')
@section('pageTitle',$pageTitle)
@section('innerTitle',$pageTitle)
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('student.dashboard')=>__lang('dashboard'),
            '#'=>$pageTitle
        ]])
@endsection

@section('content')

    <div class="row">
        @foreach($paginator as $row)
            @php  if($row->type=='c'): @endphp
            @php  $type='course';  @endphp
            @php  else: @endphp
            @php  $type='session';  @endphp
            @php  endif;  @endphp
            @php
                $course = \App\Course::find($row->course_id);
            @endphp
            <div class="col-12 col-md-4 col-lg-4">
                <article class="article article-style-c">
                    <div class="article-header">
                        <a href="{{  route('student.'.$type.'-details',['id'=>$row->course_id,'slug'=>safeUrl($row->name)]) }}">
                        @if(!empty($row->picture))
                            <div class="article-image" data-background="{{ resizeImage($row->picture,671,480,basePath()) }}">
                            </div>
                        @else
                            <div class="article-image" data-background="{{ asset('img/course.png') }}" >
                            </div>
                        @endif
                        </a>
                    </div>
                    <div class="article-details">
                        <div class="article-category"><a href="{{  route('student.'.$type.'-details',['id'=>$row->course_id,'slug'=>safeUrl($row->name)]) }}">{{ courseType($row->type) }}
                            </a> <div class="bullet"></div>
                            <a href="{{  route('student.'.$type.'-details',['id'=>$row->id,'slug'=>safeUrl($row->name)]) }}">{{ $course->lessons()->count() }} {{ __lang('classes') }}</a>
                        </div>
                        <div class="article-title">
                            {{--MARUF START--}}
                            @php
                                $course_name = explode(' - Batch ',$row->name);
                                $course_name = @$course_name[0];
                            @endphp
                            <h2><a href="{{  route('student.'.$type.'-details',['id'=>$row->course_id,'slug'=>safeUrl($row->name)]) }}">{{ $course_name }}</a></h2>
                            {{--MARUF END--}}
                        </div>
                        <div class="article-details">{{ limitLength($course->short_description,300) }}</div>

                        <div class="row pl-2">
                            @foreach($course->admins()->limit(4)->get() as $admin)

                                <div class="article-user col-md-6">
                                    <img alt="image" src="{{ profilePictureUrl($admin->user->picture) }}">
                                    <div class="article-user-details">
                                        <div class="user-detail-name">
                                            <a href="#" data-toggle="modal" data-target="#adminModal-{{ $admin->id }}">{{ limitLength(adminName($admin->id),20) }}</a>
                                        </div>
                                        <div class="text-job">{{ $admin->user->role->name }}</div>
                                    </div>
                                </div>

                                @section('footer')
                                    @parent
                                            <div class="modal fade" tabindex="-1" role="dialog" id="adminModal-{{ $admin->id }}">
                                                      <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                          <div class="modal-header">
                                                            <h5 class="modal-title">{{ $admin->user->name }} {{ $admin->user->last_name }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                              <span aria-hidden="true">&times;</span>
                                                            </button>
                                                          </div>
                                                          <div class="modal-body">
                                                              <div class="row">
                                                                  <div class="col-md-3">
                                                                      <figure class="avatar mr-2 avatar-xl text-center">
                                                                          <img src="{{ profilePictureUrl($admin->user->picture) }}"  >
                                                                      </figure>
                                                                  </div>
                                                                  <div class="col-md-p"><p>{!! clean($admin->about) !!}</p></div>
                                                              </div>

                                                          </div>
                                                          <div class="modal-footer bg-whitesmoke br">
                                                            <button type="button" class="btn btn-primary" data-dismiss="modal">{{ __lang('close') }}</button>
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                @endsection
                            @endforeach
                        </div>



                        <div class="article-footer">

                            <div class="row">
                                <div class="col-md-12">
                                    <a class="btn btn-primary btn-block" href="{{  route('student.'.$type.'-details',['id'=>$row->course_id,'slug'=>safeUrl($row->name)]) }}"><i class="fa fa-info-circle"></i> {{ __lang('details') }}</a>
                                </div>
                            </div>

                        </div>
                    </div>

                </article>
            </div>


        @endforeach

    </div>
    <div class="justify-content-center">
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
                    route('student.student.mysessions')
                );

        @endphp
    </div>


@endsection

@section('header')
    <style>
        .pagination {
            display: flex;
            justify-content: center;
        }

        .pagination li {
            display: block;
        }
    </style>
@endsection
