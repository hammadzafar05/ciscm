@extends('layouts.student')
@section('pageTitle',$pageTitle)
@section('innerTitle',$pageTitle)
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('student.dashboard')=>__lang('dashboard'),
            route('student.test.index')=>__lang('tests'),
            '#'=> __lang('test-results')
        ]])
@endsection

@section('content')
<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>{{ __lang('taken-on') }}</th>
            <th>{{ __lang('Score') }}</th>
            <th>{{ __lang('Grade') }}</th>
            <th>{{ __lang('exam_type') }}</th>
            <th>{{ __lang('Status') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rowset as $row)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ showDate('d/M/Y',$row->created_at) }}</td>
                <td>{{ round($row->score) }}</td>
                <td>{{ $gradeTable->getGrade($row->score) }}</td>
                <td>{{ __lang($test->exam_type == 0 ? 'shomvabona-mcq' : 'shomvabona-written') }}</td>
                <td>
                    @if($test->exam_type == 0)
                        @if($row->score >= $test->passmark)
                            <span style="color: green">{{ __lang('Passed') }}</span>
                        @else
                            <span style="color: red">{{ __lang('Failed') }}</span>
                        @endif
                    @else
                        @if($row->status == 'Evaluated')
                            @if($row->score >= $test->passmark)
                                <span style="color: green">{{ __lang('Passed') }}</span>
                            @else
                                <span style="color: red">{{ __lang('Failed') }}</span>
                            @endif
                        @else
                            <span style="color: red">{{ __lang($row->status) }}</span>
                        @endif
                    @endif
                </td>

            </tr>

            @endforeach
    </tbody>


</table>

{!! $rowset->links() !!}
@endsection
