@extends('layouts.student')
@section('pageTitle',$pageTitle)
@section('innerTitle',$pageTitle)
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('student.dashboard')=>__lang('dashboard'),
            '#'=>__lang('my-submissions')
        ]])
@endsection

@section('content')
    <div class="row">
        <div>
            <div class="card">

                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>{{  __lang('homework')  }}</th>
                            <th>{{ __lang('course-session') }}</th>
                            <th>{{  __lang('due-date')  }}</th>
                            <th>{{  __lang('submitted-on')  }}</th>
                            <th>{{  __lang('submission-status')  }}</th>
                            <th>{{  __lang('review-status')  }}</th>
                            <th>{{  __lang('grade')  }}</th>
                            <th>Comments</th>
                            <th></th>
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
                            <td>{{  showDate('d/M/Y',$row->due_date) }}</td>
                            <td>{{  showDate('d/M/Y',$row->created_at) }}</td>
                            <td>{!!  ($row->submitted==1)? '<span style="color:green; font-weight:bold">'.__lang('submitted').'</span>':'<span style="color:red; font-weight:bold">'.__lang('draft').'</span>' !!}  </td>
                            <td>
                                @php
                                    $now = time(); // or your date as well
                                    $your_date = strtotime($row->created_at);
                                    $datediff = $now - $your_date;

                                    $difference = round($datediff / (60 * 60 * 24));
                                @endphp


                                @if($row->passmark == 0)
                                
                                <strong style="color:blue">Learning Purpose</strong>
                                    
                                @else
                                    
                                    
                                    @if($row->created_at == $row->updated_at)
                                        @if($difference >= 3)
                                            {{  (is_null($row->grade))? __lang('pending'):__lang('graded')  }}
                                        @endif
                                    @else
                                        {{  (is_null($row->grade))? __lang('pending'):__lang('graded')  }}
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if($row->passmark == 0)
                                    @if($row->created_at == $row->updated_at)
                                         <strong style="color:blue">Not Applicable</strong>
                                    @else
                                        @php  if(!is_null($row->grade)): @endphp
                                        {{  $row->grade }}%
                                            @php  if($row->grade >= $row->passmark): @endphp
                                                <strong style="color: green">({{  __lang('passed')  }})</strong>
                                            @php  else:  @endphp
                                                <strong style="color: red">({{  __lang('failed')  }})</strong>
                                            @php  endif;  @endphp
                                        @php  else:  @endphp
                                        N/A [Not Applicable]
                                        @php  endif;  @endphp
                                    @endif
                                @else
                                    @php  if(!is_null($row->grade)): @endphp
                                        {{  $row->grade }}%
                                        @php  if($row->grade >= $row->passmark): @endphp
                                            <strong style="color: green">({{  __lang('passed')  }})</strong>
                                        @php  else:  @endphp
                                            <strong style="color: red">({{  __lang('failed')  }})</strong>
                                        @php  endif;  @endphp
                                    @php  else:  @endphp
                                    N/A [Not Applicable]
                                    @php  endif;  @endphp
                                @endif
                            </td>
                            
                            <td>{{ $row->admin_comment??""}}</td>
                            <td>
                                <div class="dropdown dropup">
                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                        {{__lang('actions')}}
                                    </button>
                                    <div class="dropdown-menu wide-btn">
                                        <a class="dropdown-item" href="{{  route('student.assignment.edit',['id'=>$row->id]) }}"><i class="fa fa-edit"></i> {{  __lang('edit')  }}
                                        </a>
                                        <a class="dropdown-item" onclick="return confirm('{{ __lang('submission-delete-confirm') }}')"
                                           href="{{  route('student.assignment.delete',['id'=>$row->id]) }}"><i class="fa fa-trash"></i> {{  __lang('delete')  }}</a>
                                        <a class="dropdown-item"
                                           onclick="openModal('{{ __lang('assignment-submission') }}: {{  addslashes($row->title)  }}','{{  route('student.assignment.view',['id'=>$row->id]) }}')"
                                           href="#"><i class="fa fa-eye"></i> {{  __lang('view')  }}</a>
                                    </div>
                                </div>

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
                            route('student.assignment.submissions')
                        );
                    @endphp
                </div><!--end .box-body -->
            </div><!--end .box -->
        </div><!--end .col-lg-12 -->
    </div>

    <!-- START SIMPLE MODAL MARKUP --><!-- /.modal -->
    <!-- END SIMPLE MODAL MARKUP -->

@endsection
