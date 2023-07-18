@extends('admin.report.report')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.report.index')=>__lang('reports'),
            '#'=>__lang('tests')
        ]])
@endsection



@section('content')
    <div>
        <!-- Nav tabs -->
        <ul class="nav nav-pills" role="tablist">
            <li   class="nav-item"><a class="nav-link active" href="#home" aria-controls="home" role="tab" data-toggle="tab">{{__lang('overview')}}</a></li>
            <li  class="nav-item"><a  class="nav-link" href="#profile" aria-controls="profile" role="tab" data-toggle="tab">{{__lang('student-scores')}}</a></li>
            <li  class="nav-item"><a  class="nav-link" href="#cards" aria-controls="cards" role="tab" data-toggle="tab">{{__lang('report-cards')}}</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="home">
                <table class="table table-striped datatable">
                    <thead>
                    <tr>
                        <th>{{__lang('test')}}</th>
                        <th>{{__lang('questions')}}</th>
                        <th>{{__lang('passmark')}}</th>
                        <th>{{__lang('attempts')}}</th>
                        <th>{{__lang('created-by')}}</th>
                        <th>{{__lang('average-score')}}</th>
                        <th>{{__lang('average-grade')}}</th>
                        <th>{{__lang('total-passed')}}</th>
                        <th>{{__lang('total-failed')}}</th>
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
                                <td>{{ $test->studentTests()->count() }}</td>
                                <td>{{ $test->admin->user->name }} {{ $test->admin->user->last_name }}</td>
                                <td>{{ round($test->studentTests()->avg('score'),1) }}</td>
                                <td>{{ $testGradeTable->getGrade($test->studentTests()->avg('score')) }}</td>
                                <td>{{ $test->studentTests()->where('score','>=',$test->passmark)->count() }}</td>
                                <td>{{ $test->studentTests()->where('score','<',$test->passmark)->count() }}</td>
                            </tr>
                        @endif
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
                        @foreach($tests as $test)
                            <th>
                                {{ limitLength($test->name,30) }}
                            </th>

                            @endforeach

                    </tr>
                    </thead>
                    <tbody>
                        @foreach($rowset as $row)
                            @php $student = \App\Student::find($row->id)  @endphp
                            @if($student)
                                <tr>
                                @php  $stats = $controller->getStudentTestsStats($row->id);  @endphp
                                <td>{{ $student->user->name }} {{ $student->user->last_name }}</td>
                                <td>{{ round($stats['average'],1) }}%</td>
                                <td>{{ $testGradeTable->getGrade($stats['average']) }}</td>
                                @foreach($tests as $test)
                                   <td>
                                       @php  $result =$test->studentTests()->where('student_id',$student->id)->orderBy('score','desc')->first()  @endphp
                                       @if($result)
                                           {{ round($result->score,1) }}% ({{ $testGradeTable->getGrade($result->score) }})
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
            <div role="tabpanel" class="tab-pane" id="cards">
                <table class="table-stripped table" id="reportcards">
                    <thead>
                    <tr>
                        <th>{{__lang('student')}}</th>
                        <th>{{__lang('average-score')}}</th>
                        <th>{{__lang('average-grade')}}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rowset as $row)
                        @php $student = \App\Student::find($row->id)  @endphp
                        @if($student)
                            @php  $stats = $controller->getStudentTestsStats($row->id);  @endphp
                            <tr>
                                <td>{{ $student->user->name }} {{ $student->user->last_name }}</td>
                                <td>{{ round($stats['average'],1) }}%</td>
                                <td>{{ $testGradeTable->getGrade($stats['average']) }}</td>
                                <td><a class="btn btn-primary" href="{{ adminUrl(['controller'=>'report','action'=>'reportcard','id'=>$student->id]) }}?sessionId={{ $session->id }}"><i class="fa fa-download"></i> {{__lang('Download')}}</a></td>
                            </tr>

                        @endif

                    @endforeach

                    </tbody>

                </table>


                </div>
        </div>

    </div>





@endsection

@section('footer')
    @parent
     <script type="text/javascript">
        $(function(){
            $('#reportcards').DataTable();
        });
    </script>
@endsection
