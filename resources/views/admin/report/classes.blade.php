@extends('admin.report.report')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.report.index')=>__lang('reports'),
            '#'=>__lang('classes')
        ]])
@endsection

@section('content')



    <div>

        <!-- Nav tabs -->
        <ul class="nav nav-pills" role="tablist">
            <li  class="nav-item"><a class="nav-link active"  href="#home" aria-controls="home" role="tab" data-toggle="tab">{{__lang('Report')}}</a></li>
            <li  class="nav-item"><a class="nav-link"  href="#profile" aria-controls="profile" role="tab" data-toggle="tab">{{__lang('Totals')}}</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="home">
                <div class="table-responsive">
                <table class="table table-striped datatable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{__lang('classes')}}</th>
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
                            $totalAttended = $attendanceTable->getTotalStudentsForSessionAndLesson($session->session_id,$row->id);
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

                </table>
            </div>
        </div>

    </div>










@endsection
