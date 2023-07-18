@extends('layouts.admin')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>__lang('courses-sessions')
        ]])
@endsection

@section('search-form')
    <form class="form-inline mr-auto" method="get" action="{{ route('admin.student.sessions') }}">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
        </ul>
        <div class="search-element">
            <input value="{{ request()->get('filter') }}"   name="filter" class="form-control" type="search" placeholder="{{ __lang('search') }}" aria-label="{{ __lang('search') }}" data-width="250">
            <button class="btn" type="submit"><i class="fas fa-search"></i></button>
        </div>
    </form>
@endsection

@section('footer')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var change_course_status = function(id,name,pk,table,value){

            /*var name = jQuery("select#"+id).data('name');
            alert('id-->'+id)
            var pk = jQuery("select#"+id).data('pk');
            var table = jQuery("select#"+id).data('table');
            var value = jQuery("select#"+id).val();*/

            jQuery("#typechangeLoader"+id).html('<i class="fa fa-spin fa-spinner"></i>');
            var datastring = "name="+name+"&pk="+pk+"&table="+table+"&value="+value;

            $.ajax({
                type: "POST",
                '_token': '{{ csrf_token() }}',
                url: "./sessions/editable_course/update_course_status",
                data: datastring,
                success: function(response) {
                    alert(response.message);
                    jQuery("#typechangeLoader"+id).html('');
                },
                error: function(jqXHR, exception) {
                    if (jqXHR.status === 0) {
                        alert('Not connect. Verify Network.');
                    } else if (jqXHR.status == 404) {
                        alert('Requested page not found. [404].');
                    } else if (jqXHR.status == 500) {
                        alert('Internal Server Error [500].');
                    } else if (exception === 'parsererror') {
                        alert('Requested JSON parse failed.');
                    } else if (exception === 'timeout') {
                        alert('Time out error.');
                    } else if (exception === 'abort') {
                        alert('Ajax request aborted.');
                    } else {
                        alert('Uncaught Error.' + jqXHR.responseText);
                    }
                    $('button[name=nameSubmitBtn]').button('reset');
                }
            });
            return false;
        }

    </script>

@endsection

@section('content')

    <section class="section">
        <div class="dropdown d-inline mr-2">
            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{ __lang('add-new') }}
            </button>
            <div class="dropdown-menu wide-btn">
                <a class="dropdown-item" href="{{ route('admin.session.addcourse')  }}">{{ __lang('online-course') }}</a>
                <a class="dropdown-item" href="{{ route('admin.student.addsession',['type'=>'s'])  }}">{{ __lang('training-session') }}</a>
                <a class="dropdown-item" href="{{ route('admin.student.addsession',['type'=>'b'])  }}">{{ __lang('training-online') }}</a>
            </div>
        </div>
        <button class="btn btn-success"  data-toggle="collapse" href="#collapseFilter" role="button" aria-expanded="false" aria-controls="collapseFilter"><i class="fa fa-filter"></i> {{ __lang('filter') }}</button>
        <br> <br>
        <div class="collapse" id="collapseFilter">
            <div class="card card-body">
                <form id="filterform"   role="form"  method="get" action="{{ route('admin.student.sessions')  }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="sr-only" for="filter">{{ __lang('filter') }}</label>
                                {{ formElement($text) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="sr-only" for="group">{{ __lang('category') }}</label>
                                {{ formElement($select) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="sr-only" for="group">{{ __lang('sort') }}</label>
                                {{ formElement($sortSelect) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="sr-only" for="group">{{ __lang('type') }}</label>
                                {{ formElement($typeSelect) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="sr-only" for="group">{{ __lang('payment-required') }}</label>
                                {{ formElement($paymentSelect) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> {{ __lang('filter') }}</button>
                            <button type="button" onclick="$('#filterform input, #filterform select').val(''); $('#filterform').submit();" class="btn btn-success"><i class="fa fa-redo"></i> {{ __lang('clear') }}</button>

                        </div>

                    </div>
                </form>
            </div>
        </div>
    <div class="row">
        @foreach($paginator as $row)
            @php
            $course = \App\Course::find($row->id);
             @endphp
        <div class="col-12 col-md-4 col-lg-4">
            <article class="article article-style-c">
                <div class="article-header">
                    @if(!empty($row->picture))
                    <div class="article-image" data-background="{{ resizeImage($row->picture,671,480,basePath()) }}">
                    </div>
                    @else
                        <div class="article-image" data-background="{{ asset('img/course.png') }}" >
                        </div>
                    @endif
                </div>
                @php
                $style = 'background-color: #17a2b826;';
                if ($row->approved_by_super_admin == 'Pending'){
                    $style = 'background-color: #ffc10775;';
                }else if ($row->approved_by_super_admin == 'Rejected'){
                    $style = 'background-color: #dc354554;';
                }
                @endphp
                <div class="article-details" style="{{ $style }}">
                    <div class="article-category"><a href="#">{{ courseType($row->type) }}
                        </a> <div class="bullet"></div> <a href="#" onclick="openModal('{{ __lang('students-for') }} {{ $row->name}}','{{ route('admin.student.sessionenrollees',['id'=>$row->id])  }}')">{{ $studentSessionTable->getTotalForSession($row->id) }} {{ __lang('students') }}</a></div>
                    <div class="article-title">
                        <h2><a href="{{ route('admin.student.editsession',['id'=>$row->id])  }}">{{ $row->name }} [{{ $row->approved_by_super_admin }}]</a></h2>
                    </div>

                    @if($admin_role  == 'Super Administrator')
                        <fieldset>
                            {{--<legend>Status</legend>--}}
                            <select id="approved_by_super_admin_{{ $row->id }}"
                                    name="approved_by_super_admin[{{ $row->id }}]"
                                    class="form-control required"
                                    onchange="change_course_status({{ $row->id }},'approved_by_super_admin',{{ $row->id }},'courses',this.value)">
                                <option value="Pending" {{ $row->approved_by_super_admin == 'Pending' ? 'selected="selected"' : '' }}>Pending</option>
                                <option value="Accepted" {{ $row->approved_by_super_admin == 'Accepted' ? 'selected="selected"' : '' }}>Accepted</option>
                                <option value="Rejected" {{ $row->approved_by_super_admin == 'Rejected' ? 'selected="selected"' : '' }}>Rejected</option>
                            </select>
                        </fieldset>
                    @endif


                    @if(\App\Admin::find($row->admin_id))
                    <div class="article-user">
                        <img alt="image" src="{{ profilePictureUrl(\App\Admin::find($row->admin_id)->user->picture) }}">
                        <div class="article-user-details">
                            <div class="user-detail-name">
                                <a href="#">{{ adminName($row->admin_id) }}</a>
                            </div>
                            <div class="text-job">{{ \App\Admin::find($row->admin_id)->user->role->name }}</div>
                        </div>
                    </div>
                        @endif

                    <div class="article-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <button data-toggle="modal" data-target="#infoModal-{{ $row->id }}" class="btn btn-block btn-primary"><i class="fa fa-info-circle"></i> {{ __lang('info') }}</button>
                            </div>
                            <div class="col-md-6">
                                <div class="btn-group dropup hundred-percent">
                                    <button class="btn btn-block btn-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-file-export"></i> {{ __lang('export') }}
                                    </button>
                                    <div class="dropdown-menu wider-btn">
                                        <a class="dropdown-item has-icon"
                                           href="{{ route('admin.student.export',['id'=>$row->id])  }}">
                                            <i class="fa fa-users"></i>  {{ __lang('export-students') }}
                                        </a>
                                        <a class="dropdown-item has-icon"
                                           href="{{ route('admin.student.export_results',['id'=>$row->id])  }}">
                                            <i class="fa fa-users"></i>  <font color="blue"><b>Export Student for Results</b></font>
                                        </a>
                                        <a class="dropdown-item has-icon" href="{{ route('admin.student.exportbulkattendance',['id'=>$row->id])  }}"   ><i class="fa fa-users"></i> {{ __lang('export-students') }} - {{ __lang('attendance-import') }}</a>
                                        @if($row->type != 'c')
                                            <a class="dropdown-item has-icon" target="_blank" href="{{ route('admin.student.exportattendance',['id'=>$row->id]) }}"><i class="fa fa-table"></i> {{ __lang('attendance-sheet') }}</a>
                                        @endif
                                        <a class="dropdown-item has-icon" href="{{ route('admin.student.exporttel',['id'=>$row->id]) }}"   ><i class="fa fa-phone"></i> {{ __lang('telephone-numbers') }}</a>

                                    </div>
                                </div>
                            </div>

                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="btn-group dropup hundred-percent">
                                    <button class="btn btn-block btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-cogs"></i> {{ __lang('actions') }}
                                    </button>
                                    <div class="dropdown-menu wider-btn">
                                        @if($row->type != 'c')
                                            <a class="dropdown-item has-icon" href="{{ route('admin.student.editsession',['id'=>$row->id]) }}"><i class="fa fa-edit"></i> {{ __lang('edit') }}</a>
                                            <a class="dropdown-item has-icon" href="{{ route('admin.session.sessionclasses',['id'=>$row->id]) }}"><i class="fa fa-desktop"></i> {{ __lang('manage-classes') }}</a>
                                        @else
                                            <a class="dropdown-item has-icon" href="{{ route('admin.session.editcourse',['id'=>$row->id]) }}"><i class="fa fa-edit"></i> {{ __lang('edit') }}</a>
                                            <a class="dropdown-item has-icon" href="{{ route('admin.session.courseclasses',['id'=>$row->id]) }}"><i class="fa fa-desktop"></i> {{ __lang('manage-classes') }}</a>
                                            <a class="dropdown-item has-icon"  target="_blank" href="{{ route('admin.course.intro',['id'=>$row->id]) }}"><i class="fa fa-play"></i> {{ __lang('try-course') }}</a>
                                        @endif
                                        <a class="dropdown-item has-icon" href="{{ route('admin.student.sessionstudents',['id'=>$row->id]) }}"><i class="fa fa-users"></i> {{ __lang('view-enrolled') }}</a>
                                        <a class="dropdown-item has-icon" href="{{ route('admin.student.sessionstudents',['id'=>$row->id]) }}"><i class="fa fa-file-alt"></i> {{ __lang('prepare-results') }}</a>

                                            @if($admin_role != 'Partner')
                                            @if($row->type != 'c')
                                                <a class="dropdown-item has-icon" href="{{ route('admin.student.instructors',['id'=>$row->id]) }}"><i class="fa fa-user"></i> {{ __lang('manage-instructors') }}</a>
                                            @endif
                                        @endif
                                            <a class="dropdown-item has-icon" href="{{ route('admin.student.mailsession',['id'=>$row->id]) }}"><i class="fa fa-envelope"></i> {{ __lang('send-message-enrolled') }}</a>
                                            @if($admin_role != 'Partner')
                                                <a class="dropdown-item has-icon"
                                                   href="{{ route('admin.student.duplicatesession',['id'=>$row->id]) }}">
                                                    <i class="fa fa-copy"></i> {{ __lang('duplicate') }}
                                                </a>
                                                {{--MARUF START--}}
                                                <a class="dropdown-item has-icon"
                                                   href="{{ route('admin.student.generate_batch',['id'=>$row->id,'copy'=>'single']) }}">
                                                    <i class="fa fa-seedling"></i> <font color="#1e90ff">{{ __lang('generate_batch') }} without Material</font>
                                                </a>
                                                <a class="dropdown-item has-icon"
                                                   href="{{ route('admin.student.generate_batch',['id'=>$row->id,'copy'=>'all']) }}">
                                                    <i class="fa fa-seedling"></i> <font color="red">{{ __lang('generate_batch') }} with Material</font>
                                                </a>
                                                
                                                
                                                {{--MARUF END--}}
                                                
                                                <!--kabir works=========-->
                                                  <a target="_blank" href="{{url('admin/student/add-certificate-badge/'.$row->id)}}" class="dropdown-item has-icon"><i class="fa fa-edit"></i>{{ __lang('generate_certificate')}}</a>
                                                <!--kabir works end-->


                                                {{----Starting WU-35 : Question Bank----}}
                                                <a class="dropdown-item has-icon"
                                                   href="{{ route('admin.sessions.question_banks',['id'=>$row->id]) }}">
                                                    <i class="fa fa-seedling"></i> <font color="blue">Manage Questions Bank</font>
                                                </a>
                                                {{----Ending WU-35 : Question Bank----}}
                                            @endif

                                            @if($row->type != 'c')
                                            <a class="dropdown-item has-icon"  onclick="openModal('{{ __lang('change-type') }}: {{ addslashes($row->name) }}','{{ route('admin.session.sessiontype',['id'=>$row->id]) }}')" href="#" ><i class="fa fa-arrows-alt-v"></i> {{ __lang('change-session-type') }}</a>
                                        @endif
                                        <a class="dropdown-item has-icon" href="{{ route('admin.session.tests',['id'=>$row->id]) }}"><i class="fa fa-check"></i> {{ __lang('manage-tests') }}</a>
                                        <a class="dropdown-item has-icon" onclick="return confirm('{{__lang('delete-confirm')}}')" href="{{ route('admin.student.deletesession',['id'=>$row->id]) }}"   ><i class="fa fa-trash-alt"></i> {{ __lang('delete') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </article>
        </div>
            @section('footer')
                @parent
            <div class="modal fade" tabindex="-1" role="dialog" id="infoModal-{{ $row->id }}">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $row->name }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <ul class="nav nav-pills" id="myTab3-{{ $row->id }}" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab3-{{ $row->id }}" data-toggle="tab" href="#home3-{{ $row->id }}" role="tab" aria-controls="home" aria-selected="true">{{ __lang('general') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab3-{{ $row->id }}" data-toggle="tab" href="#profile3-{{ $row->id }}" role="tab" aria-controls="profile" aria-selected="false">{{ __lang('totals') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="contact-tab3-{{ $row->id }}" data-toggle="tab" href="#contact3-{{ $row->id }}" role="tab" aria-controls="contact" aria-selected="false">{{ __lang('classes') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="contact-tab4-{{ $row->id }}" data-toggle="tab" href="#contact4-{{ $row->id }}" role="tab" aria-controls="contact4" aria-selected="false">{{ __lang('instructors') }}</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent2-{{ $row->id }}">
                                <div class="tab-pane fade show active" id="home3-{{ $row->id }}" role="tabpanel" aria-labelledby="home-tab3-{{ $row->id }}">


                                    <div class="section-title mt-0 tab-list-title">@lang('default.name')</div>
                                    <blockquote class="plain">
                                        {{ $row->name }}
                                    </blockquote>
                                    <div class="section-title mt-0 tab-list-title">@lang('default.short-description')</div>
                                    <blockquote class="plain">
                                        {{ $row->short_description }}
                                    </blockquote>
                                    <div class="section-title mt-0 tab-list-title">@lang('default.description')</div>
                                    <blockquote class="plain">
                                        {!! clean($row->description)  !!}
                                    </blockquote>
                                    @if(!empty($row->introduction))
                                    <div class="section-title mt-0 tab-list-title">@lang('default.introduction')</div>
                                    <blockquote class="plain">
                                        {!! clean($row->introduction)  !!}
                                    </blockquote>
                                    @endif

                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="section-title mt-0 tab-list-title">@lang('default.created-by')</div>
                                            <blockquote class="plain">
                                                {{ adminName($row->admin_id) }}
                                            </blockquote>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="section-title mt-0 tab-list-title">@lang('default.status')</div>
                                            <blockquote class="plain">
                                                {{ empty($row->enabled)? __lang('disabled'):__lang('enabled') }}
                                            </blockquote>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="section-title mt-0 tab-list-title">{{ __lang('start-date') }}</div>
                                            <blockquote class="plain">
                                                {{ showDate('d/M/Y',$row->start_date) }}
                                            </blockquote>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="section-title mt-0 tab-list-title">{{ __lang('end-date') }}</div>
                                            <blockquote class="plain">
                                                {{ showDate('d/M/Y',$row->end_date) }}
                                            </blockquote>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="section-title mt-0 tab-list-title">{{ __lang('enrollment-closes') }}</div>
                                            <blockquote class="plain">
                                                {{ showDate('d/M/Y',$row->enrollment_closes) }}
                                            </blockquote>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="section-title mt-0 tab-list-title">{{ __lang('payment-required') }}</div>
                                            <blockquote class="plain">
                                                {{ boolToString($row->payment_required) }}
                                            </blockquote>
                                        </div>
                                    @if(!empty($row->capacity))
                                        <div class="col-md-6">
                                            <div class="section-title mt-0 tab-list-title">{{ __lang('capacity') }}</div>
                                            <blockquote class="plain">
                                                {{ $row->capacity }}
                                            </blockquote>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="section-title mt-0 tab-list-title">{{ __lang('enforce-capacity') }}</div>
                                            <blockquote class="plain">
                                                {{ boolToString($row->enforce_capacity) }}
                                            </blockquote>
                                        </div>
                                    @endif
                                        <div class="col-md-6">
                                            <div class="section-title mt-0 tab-list-title">{{ __lang('course-fee') }}</div>
                                            <blockquote class="plain">
                                                {{ price($row->fee) }}
                                            </blockquote>
                                        </div>
                                        @if(!empty($row->venue))
                                        <div class="col-md-6">

                                                <div class="section-title mt-0 tab-list-title">{{ __lang('venue') }}</div>
                                                <blockquote class="plain">
                                                    {{ $row->venue }}
                                                </blockquote>

                                        </div>
                                        @endif
                                        <div class="col-md-6">
                                            <div class="section-title mt-0 tab-list-title">{{ __lang('enable-chat') }}</div>
                                            <blockquote class="plain">
                                                {{ $row->enable_chat }}
                                            </blockquote>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="section-title mt-0 tab-list-title">{{ __lang('enable-discussions') }}</div>
                                            <blockquote class="plain">
                                                {{ $row->enable_discussion }}
                                            </blockquote>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="section-title mt-0 tab-list-title">{{ __lang('enforce-order') }}</div>
                                            <blockquote class="plain">
                                                {{ boolToString($row->enforce_order) }}
                                            </blockquote>
                                        </div>

                                        @if(!empty($row->effort))
                                        <div class="col-md-6">

                                                <div class="section-title mt-0 tab-list-title">{{ __lang('effort') }}</div>
                                                <blockquote class="plain">
                                                    {{ $row->effort }}
                                                </blockquote>

                                        </div>
                                        @endif
                                        @if(!empty($row->length))
                                        <div class="col-md-6">

                                                <div class="section-title mt-0 tab-list-title">{{ __lang('length') }}</div>
                                                <blockquote class="plain">
                                                    {{ $row->length }}
                                                </blockquote>

                                        </div>
                                        @endif
                                        <div class="col-md-6">
                                            <div class="section-title mt-0 tab-list-title">{{ __lang('enable-forum') }}</div>
                                            <blockquote class="plain">
                                                {{ boolToString($row->enable_forum) }}
                                            </blockquote>
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane fade" id="profile3-{{ $row->id }}" role="tabpanel" aria-labelledby="profile-tab3-{{ $row->id }}">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td>{{ __lang('enrolled-students') }}</td>
                                            <td>{{ $studentSessionTable->getTotalForSession($row->id) }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __lang('total-attended') }}</td>
                                            <td>{{ $attendanceTable->getTotalStudentsForSession($row->id) }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __lang('classes') }}</td>
                                            <td>{{ $course->lessons()->count() }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __lang('homework') }}</td>
                                            <td>{{ $course->assignments()->count() }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __lang('revision-notes') }}</td>
                                            <td>{{ $course->revisionNotes()->count() }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __lang('tests') }}</td>
                                            <td>{{ $course->tests()->count() }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __lang('downloads') }}</td>
                                            <td>{{ $course->downloads()->count() }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __lang('forum-topics') }}</td>
                                            <td>{{ $course->forumTopics()->count() }}</td>
                                        </tr>

                                    </table>

                                </div>
                                <div class="tab-pane fade" id="contact3-{{ $row->id }}" role="tabpanel" aria-labelledby="contact-tab3-{{ $row->id }}">
                                    <table class="table tab-bordered">
                                        <thead>
                                            <tr>
                                                <th>{{ __lang('class') }}</th>
                                                <th>{{ __lang('type') }}</th>
                                                <th>{{ __lang('start-date') }}</th>
                                                <th>{{ __lang('starts') }}</th>
                                                <th>{{ __lang('ends') }}</th>
                                                @if($course->type!='c')
                                                <th>{{ __lang('venue') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($course->lessons()->orderBy('sort_order')->get() as $lesson)
                                                <tr>
                                                    <th>{{ $lesson->name }}</th>
                                                    <th>{{ lessonType($lesson->type) }}</th>
                                                    <th>{{ showDate('d/M/Y',$lesson->pivot->lesson_date) }}</th>
                                                    <th>{{ $lesson->pivot->lesson_start }}</th>
                                                    <th>{{ $lesson->pivot->lesson_end }}</th>
                                                    @if($course->type!='c')
                                                        <th>{{ $lesson->pivot->lesson_venue }}</th>
                                                    @endif
                                                </tr>

                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="contact4-{{ $row->id }}" role="tabpanel" aria-labelledby="contact-tab4-{{ $row->id }}">
                                  <table class="table table-stripped">
                                      <thead>
                                      <tr>
                                        <th></th>
                                        <th>{{ __lang('name') }}</th>
                                        <th>{{ __lang('email') }}</th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                        @foreach($course->admins as $admin)
                                            <tr>
                                                <td>
                                                    <figure class="avatar mr-2 avatar-xl">
                                                        <img src="{{ profilePictureUrl($admin->user->picture) }}" >
                                                    </figure>

                                                </td>
                                                <td>{{ $admin->user->name }}</td>
                                                <td><a href="mailto:{{ $admin->user->email }}">{{ $admin->user->email }}</a></td>
                                            </tr>
                                        @endforeach
                                      </tbody>
                                  </table>



                                </div>
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

        {{ paginationControl(
                // the paginator object
                    $paginator,
                    // the scrolling style
                    'sliding',
                    // the partial to use to render the control
                    null,
                    // the route to link to when a user clicks a control link
                    array(
                        'route' => 'admin/default',
                        'controller'=>'student',
                        'action'=>'sessions'
                    )
                ) }}
</section>

@endsection
