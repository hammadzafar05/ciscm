@extends('admin.report.report')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.report.index')=>__lang('reports'),
            '#'=>__lang('students')
        ]])
@endsection


@section('content')
    <div>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item"><a class="nav-link active"  href="#home" aria-controls="home" role="tab" data-toggle="tab">{{__lang('Report')}}</a></li>
            <li class="nav-item"><a class="nav-link"  href="#profile" aria-controls="profile" role="tab" data-toggle="tab">{{__lang('Totals')}}</a></li>
            <li class="nav-item"><a class="nav-link"  href="#messages" aria-controls="messages" role="tab" data-toggle="tab">{{__lang('Classes')}}</a></li>
            <li class="nav-item"><a class="nav-link"  href="#settings" aria-controls="settings" role="tab" data-toggle="tab">{{__lang('Tests')}}</a></li>
            <li class="nav-item"><a class="nav-link"  href="#homework" aria-controls="homework" role="tab" data-toggle="tab">{{__lang('Homework')}}</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="home">
                <div class="table-responsive">
                <table class="table table-striped datatable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{__lang('student-name')}}</th>
                        <th>{{__lang('enrolled-on')}}</th>
                        <th>{{__lang('classes-attended')}}</th>
                        <th>{{__lang('progress')}}</th>
                        <th>{{__lang('tests-taken')}}</th>
                        <th>{{__lang('average-test-score')}}</th>
                        <th>{{__lang('test-grade')}}</th>
                        <th>{{__lang('homework-submitted')}}</th>
                        <th>{{__lang('average-homework-score')}}</th>
                        <th>{{__lang('homework-grade')}}</th>
                        <th>{{__lang('instructor-chats')}}</th>
                        <th>{{__lang('forum-topics')}}</th>
                        <th>{{__lang('forum-posts')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rowset as $row)
                        @php $student = \App\Student::find($row->id)  @endphp
                        @if($student)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $student->user->name.' '.$student->user->last_name }}</td>
                                <td>
                                    @php
                                    $enrollment = $student->studentCourses()->where('course_id',$id)->first();
                                     @endphp
                                    @if($enrollment)
                                        {{ showDate('d/M/Y',$enrollment->created_at) }}
                                    @endif
                                </td>
                                @php
                                $attendance = $student->attendances()->where('course_id',$id)->count();
                                @endphp
                                <td>{{ $attendance }}</td>

                                <td>

                                    @php
                                    echo round(($attendance/$totalSessionLessons)*100)
                                    @endphp%
                                </td>
                                @php
                                $testStats = $controller->getStudentTestsStats($row->id);
                                @endphp
                                <td>
                                    {{ $testStats['testsTaken'] }}
                                </td>
                                <td>
                                    {{ $testStats['average'] }}
                                </td>
                                <td>
                                    {{ $testGradeTable->getGrade($testStats['average']) }}
                                </td>
                                @php
                                $homeworkStats = $controller->getStudentAssignmentStats($row->id);
                                @endphp
                                <td>
                                    {{ $homeworkStats['submissions'] }}
                                </td>
                                <td>
                                    {{ $homeworkStats['average'] }}
                                </td>
                                <td>
                                    {{ $testGradeTable->getGrade($homeworkStats['average']) }}
                                </td>

                                <td>
                                    {{ $student->discussions()->where('course_id',$id)->count() }}
                                </td>
                                <td>
                                    {{ $student->user->forumTopics()->where('course_id',$id)->whereHas('user',function ($q){
                                            $q->where('role_id',2);
                                    })->count() }}
                                </td>
                                <td>
                                    {{ $controller->getStudentTotalPosts($row->id) }}
                                </td>


                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="profile">
                <table class="table table-striped">
                    <tr>
                        <td style="width: 30%">{{__lang('enrolled-students')}}:</td>
                        <td>{{ $session->studentCourses()->count() }}</td>
                    </tr>
                    <tr>
                        <td>{{__lang('total-classes')}}:</td>
                        <td>{{ $session->lessons()->count() }}</td>
                    </tr>
                    <tr>
                        <td>{{__lang('total-students-attended')}}:</td>
                        <td>{{ $attendanceTable->getTotalStudentsForSession($id) }}</td>
                    </tr>
                    <tr>
                        <td>{{__lang('total-tests')}}:</td>
                        <td>{{ count($allTests) }}</td>
                    </tr>
                    <tr>
                        <td>{{__lang('total-homework')}}:</td>
                        <td>{{ $session->assignments()->count() }}</td>
                    </tr>
                </table>
            </div>
            <div role="tabpanel" class="tab-pane" id="messages">
                <table class="table table-striped datatable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{__lang('class')}}</th>
                        @if($session->type=='c')
                            <th>{{__lang('lectures')}}</th>
                        @endif
                        <th>{{__lang('students-completed')}}</th>
                        <th>{{__lang('completion-percentage')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($session->lessons()->orderBy('pivot_sort_order')->orderBy('pivot_lesson_date')->get() as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->name }}</td>
                            @if($session->type=='c')
                                <td>{{ $row->lectures()->count() }}</td>
                            @endif
                            @php
                            $totalAttended = $attendanceTable->getTotalStudentsForSessionAndLesson($session->id,$row->id);
                             @endphp
                            <td>{{ $totalAttended }}</td>
                            @php
                            $total = $session->studentCourses()->count();
                            if(empty($total)){
                            $total=1;
                            }
                             @endphp
                            <td>{{ ($totalAttended/$total)*100 }}%</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>



            </div>
            <div role="tabpanel" class="tab-pane" id="settings">

                <table class="table table-striped datatable">
                    <thead>
                    <tr>
                        <th>{{__lang('test')}}</th>
                        <th>{{__lang('questions')}}</th>
                        <th>{{__lang('passmark')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                            @foreach($allTests as $testId)
                                @php $test = \App\Test::find($testId);  @endphp
                                    @if($test)
                                        <tr>
                                            <td>{{ $test->name }}</td>
                                            <td>{{ $test->testQuestions()->count() }}</td>
                                            <td>{{ $test->passmark }}%</td>
                                        </tr>
                                    @endif
                                @endforeach
                    </tbody>
                </table>
            </div>
            <div role="tabpanel" class="tab-pane" id="homework">


                <table class="table table-striped datatable">
                    <thead>
                    <tr>
                        <th>{{__lang('homework')}}</th>
                        <th>{{__lang('created-on')}}</th>
                        <th>{{__lang('due-date')}}</th>
                        <th>{{__lang('created-by')}}</th>
                        <th>{{__lang('passmark')}}</th>
                        <th>{{__lang('submissions')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($session->assignments as $assignment)

                            <tr>
                                <td>{{ $assignment->title }}</td>
                                <td>{{ showDate('d/M/Y',$assignment->created_at) }}</td>
                                <td>{{ showDate('d/M/Y',$assignment->due_date) }}</td>
                                <td>{{ $assignment->admin->user->name }} {{ $assignment->admin->user->last_name }}</td>
                                <td>{{ $assignment->passmark }}%</td>
                                <td>{{ $assignment->assignmentSubmissions()->count() }}</td>
                            </tr>

                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection

