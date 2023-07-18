<!DOCTYPE html><html  {{langMeta()}}>
{{----Starting WU-71 : Written exam PDF----}}
<head>
    <title>Test Result</title>
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

    <h2 style="text-align: center">{{__lang('student')}} Answer Script</h2>
    <table class="table table-striped">
        <tr>
            <td>{{__lang('student')}}</td>
            <td>{{ $student->user->name }} {{ $student->user->last_name }}</td>
        </tr>
        <tr>
            <td>
                {{__lang('session-course')}}:
            </td>
            <td>
                {{ $session->name }}
            </td>
        </tr>
        <tr>
            <td>
                Test:
            </td>
            <td>
                {{ $test->name }}
            </td>
        </tr>
    </table>


    <h4>{{strtoupper(__lang('results'))}}</h4>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Question</th>
            <th>Answer</th>
            @if($test->exam_type == "0")
            <th>Correct</th>
            @endif
        </tr>
        </thead>
        @foreach($tests as $test)

            <tr>
                <td width="300">{!! $test->question !!}</td>
                @if($test->answer == "NULL")
                    <td>{!! $test->option !!}</td>
                @else
                <td>{!! $test->answer !!}</td>
                @endif
                @if($test->answer == "NULL")
                <td>{{ boolToString($test->is_correct) }}</td>
                @endif
            </tr>
        @endforeach
    </table>

</div>

</body>
{{----ending WU-71 : Written exam PDF----}}
</html>

