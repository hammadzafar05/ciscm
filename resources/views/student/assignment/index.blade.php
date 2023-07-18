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

    <div >
        <div class="card">
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>{{  __lang('title')  }}</th>
                        <th>{{  __lang('course')  }}</th>
                        <th>{{  __lang('created-on')  }}</th>
                        <th>{{  __lang('due-date')  }}</th>
                        <th>Attachment</th>
                        <th class="text-right1" ></th>
                    </tr>
                    </thead>
                    <tbody>
                    @php  foreach($paginator as $row):  @endphp
                        <tr>
                            <td>{{  $row->title }}</td>
                            {{--MARUF START--}}
                            <td>
                                @php
                                    $course_name = explode(' - Batch ',$row->course_name);
                                    $course_name = @$course_name[0];
                                @endphp
                                @if ((Request::ip() == '::1') OR (Request::ip() == '127.0.0.1'))
                                    {{  $row->course_name }}
                                @else
                                    {{  $course_name }}
                                @endif
                            </td>
                            {{--MARUF END--}}
                            <td>{{  showDate('d/M/Y',$row->created_at) }}</td>
                            <td class="assignment_due_date">
                                {{  showDateTime('d/M/Y h:i A',$row->due_date) }}
                            </td>
                            <td class="">
                                @if($row->assignment_file !="")
                                <a class="btn btn-primary" href="{{ url('uploads/assignment/')}}/{{ $row->assignment_file }}"><i class="fa fa-download"></i> Download</a>
                                @endif
                            </td>

                            <td class="text-right1">
                                <a class="btn btn-primary"
                                   href="{{  route('student.assignment.submit',['id'=>$row->assignment_id]) }}">
                                    <i class="fa fa-file"></i> {{  __lang('view')  }}/{{  __lang('submit-homework')  }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="readmorebox" colspan="6">

                                <article class="readmore">
                                    {!! clean($row->instruction) !!}
                                </article>
                            </td>
                        </tr>
                    @php  endforeach;  @endphp

                    </tbody>
                </table>

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
                    route('student.assignment.index')
                );
                 @endphp
            </div><!--end .box-body -->
        </div>
    </div>


@endsection


@section('footer')
    <script type="text/javascript" src="{{ asset('client/vendor/readmore/readmore.min.js') }}"></script>
    <script>
        $(function(){
            $('article.readmore').readmore({
                collapsedHeight : 200
            });
        });
    </script>
@endsection
