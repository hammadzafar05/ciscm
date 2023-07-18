@extends('admin.report.report')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
               route('admin.report.index')=>__lang('reports'),
            '#'=>__lang('homework')
        ]])
@endsection



@section('content')



    <div>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li  class="nav-item"><a  class="nav-link active" href="#home" aria-controls="home" role="tab" data-toggle="tab">{{__lang('overview')}}</a></li>
            <li class="nav-item"><a   class="nav-link" href="#profile" aria-controls="profile" role="tab" data-toggle="tab">{{__lang('student-scores')}}</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">


            <div role="tabpanel" class="tab-pane active" id="home">

                <table class="table table-striped datatable">
                    <thead>
                    <tr>
                        <th>{{__lang('homework')}}</th>
                        <th>{{__lang('created-on')}}</th>
                        <th>{{__lang('due-date')}}</th>
                        <th>{{__lang('created-by')}}</th>
                        <th>{{__lang('passmark')}}</th>
                        <th>{{__lang('submissions')}}</th>
                        <th>{{__lang('average-score')}}</th>
                        <th>{{__lang('average-grade')}}</th>
                        <th>{{__lang('total-passed')}}</th>
                        <th>{{__lang('total-failed')}}</th>
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
                            <td>{{ round($assignment->assignmentSubmissions()->avg('grade'),1) }}</td>
                            <td>{{ $testGradeTable->getGrade($assignment->assignmentSubmissions()->avg('grade')) }}</td>
                            <td>{{ $assignment->assignmentSubmissions()->where('grade','>=',$assignment->passmark)->count() }}</td>
                            <td>{{ $assignment->assignmentSubmissions()->where('grade','<',$assignment->passmark)->count() }}</td>

                        </tr>

                    @endforeach
                    </tbody>
                </table>
            </div>

            <div role="tabpanel" class="tab-pane" id="profile">
                <div class="table-responsive">
                    <table class="table table-striped datatable">
                        <thead>
                        <tr>
                            <th>{{__lang('student')}}</th>
                            <th>{{__lang('average-score')}}</th>
                            <th>{{__lang('average-grade')}}</th>
                            @foreach($session->assignments as $assignment)
                                <th>
                                    {{ limitLength($assignment->title,30) }}
                                </th>

                            @endforeach

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($rowset as $row)
                            @php $student = \App\Student::find($row->id)  @endphp
                            @if($student)
                                <tr>
                                    @php  $stats = $controller->getStudentAssignmentStats($row->id);  @endphp
                                    <td>{{ $student->user->name }} {{ $student->user->last_name }}</td>
                                    <td>{{ round($stats['average'],1) }}%</td>
                                    <td>{{ $testGradeTable->getGrade($stats['average']) }}</td>
                                    @foreach($session->assignments as $assignment)
                                        <td>
                                            @php  $result = $assignment->assignmentSubmissions()->where('student_id',$student->student_id)->orderBy('grade','desc')->first()  @endphp
                                            @if($result)
                                                {{ round($result->grade,1) }}% ({{ $testGradeTable->getGrade($result->grade) }})
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>






@endsection
