<!DOCTYPE html><html  {{langMeta()}}>

<head>
    <title>{{__lang('report-card')}}</title>
    <!-- BEGIN META -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="your,keywords">
    <meta name="description" content="Short explanation about this website">
    <!-- END META -->

    <style>
        * { font-family: DejaVu Sans, sans-serif; }
    </style>

    <!-- END STYLESHEETS -->

    <style>
        .fadedtext{
            font-size: 8px;
            color: #d9d9d9;
        }
        .table {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        .table td, .table th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .table tr:nth-child(even){background-color: #f2f2f2;}

        .table tr:hover {background-color: #ddd;}

        .table th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #4CAF50;
            color: white;
        }
    </style>



</head>


<body>
<div class="container">
    <div style="text-align: center">
    @php $logo = setting('image_logo'); if(!empty($logo)): @endphp
    <img style="max-height: 100px" class="img-responsive" src="{{ asset($logo) }}">
    @php endif;  @endphp
    </div>
    <h1 style="text-align: center;">{{ setting('general_site_name') }}</h1>

    <h2 style="text-align: center">Transcript</h2>
    <table class="table table-striped">
        <tr>
            <td>{{__lang('Candidate Name')}}</td>
            <td>{{ $student->user->name }} {{ $student->user->last_name }}</td>
        </tr>
        <tr>
            <td>
                {{__lang('session-course')}}:
            </td>
            <td>
                @php
                    $course_name = explode(' - Batch ',$session->name);
                    $course_name = @$course_name[0];
                @endphp
                {{ $course_name }}
            </td>
        </tr>
    </table>

    <div>
        <h4>{{strtoupper(__lang('results'))}}</h4>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>{{__lang('test')}}</th>
                <th>{{__lang('passmark')}}</th>
                <th>{{__lang('score')}}</th>
                <th>{{__lang('grade')}}</th>
                <th>{{__lang('status')}}</th>
            </tr>
            </thead>
            @foreach($tests as $test)
                <tr>
                    <td>{{ $test->name }}</td>
                    <td>{{ $test->passmark }}%</td>
                    <td>     @php  $result =$test->studentTests()->where('student_id',$student->id)->orderBy('score','desc')->first()  @endphp
                        @if($result)
                            {{ round($result->score,1) }}%
                        @endif
                    </td>
                    <td>
                        @if($result)
                        {{ $testGradeTable->getGrade($result->score) }}
                        @endif

                    </td>
                    <td>
                        @if($result && $result->score >= $test->passmark)
                            <span style="color: green">{{__lang('passed')}}</span>
                            @else
                            <span style="color: red">{{__lang('failed')}}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>

        <h4>Total</h4>
        <table class="table table-striped">
            @php  $stats = $controller->getStudentTestsStats($student->id);  @endphp
            <tr>
                <td style="width: 30%;">{{__lang('average-score')}}:</td>
                <td>{{ round($stats['average'],1) }}%</td>
            </tr>
            <tr>
                <td>{{__lang('average-grade')}}:</td>
                <td>{{ $testGradeTable->getGrade($stats['average']) }}</td>
            </tr>
        </table>
    </div>

    <table class="table table-striped">
        <tr>
            <td colspan="2">Modules:</td>
        </tr>
        @php
            $loop = 0;
        @endphp
        @foreach($modules as $module)
        <tr>
            <td style="width: 80px">Module {{ $loop->iteration }}:</td>
            <td>{{ $module }}</td>
        </tr>
        @endforeach
    </table>



</div>

</body>

</html>

