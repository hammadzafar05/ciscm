@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('content')
<!DOCTYPE html><html  {{langMeta()}}>

<head>
    <title>{{ __lang('attendance') }}: {{ $pageTitle }}</title>
    <!-- BEGIN META -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="your,keywords">
    <meta name="description" content="Short explanation about this website">
    <!-- END META -->

    <!-- Bootstrap -->
    <link href="{{ basePath() }}/client/themes/cpanel/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ basePath() }}/client/themes/cpanel/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="{{ basePath() }}/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.css" />


    <!-- END STYLESHEETS -->

<style>
    .fadedtext{
        font-size: 8px;
        color: #d9d9d9;
    }
</style>


</head>


<body>
<div class="container_">
    <div class="card">
        <div class="card-body">
            <h2>{{ __lang('attendance') }}: {{ $pageTitle }}</h2>
            <table class="table table-stripped table-bordered">
                <thead>
                <tr>
                    <th>{{ __lang('first-name') }}</th>
                    <th>{{ __lang('last-name') }}</th>

                    <th>{{ __lang('telephone-number') }}</th>
                    <th>{{ __lang('email') }}</th>

                    @php $count = 1 @endphp
                    @php foreach($lessons as $lesson):  @endphp

                    <th>{{ __lang('class') }} {{ $count }} @php if(!$attendanceTable->lessonExists($lesson->lesson_id,$sid)): @endphp- Start@php endif;  @endphp
                    <div style="font-size: 9px">{{ limitLength($lesson->lesson_name,20) }}</div>
                    </th>
                    @php if(!$attendanceTable->lessonExists($lesson->lesson_id,$sid)): @endphp
                    <th>{{ __lang('class') }} {{ $count }} - End
                        <div style="font-size: 9px">{{ limitLength($lesson->lesson_name,20) }}</div>
                    </th>
                    @php endif;  @endphp

                    @php $count++;  @endphp
                    @php endforeach;  @endphp





                </tr>
                </thead>
                <tbody>

                @php foreach($students as $row):  @endphp
                <tr>
                    <td style ="word-break:break-all;" >{{ ucwords(strtolower($row->name)) }}</td>
                    <td style ="word-break:break-all;" >{{ ucwords(strtolower($row->last_name)) }}</td>

                    <td style ="word-break:break-all;" >{{ cleanTel($row->mobile_number) }}</td>
                    <td style ="word-break:break-all;" >{{ strtolower($row->email) }}</td>


                    @php $count = 1 @endphp
                    @php foreach($lessons as $lesson):  @endphp


                    <td style ="word-break:break-all;" class="fadedtext" >@php if(!$attendanceTable->lessonExists($lesson->lesson_id,$sid)): @endphpClass {{ $count }} - Start@php else: @endphp
                        @php if($attendanceTable->hasAttendance($row->student_id,$lesson->lesson_id,$sid)): @endphp

                                <img src="{{ basePath()}}/images/checkmark.png" style="height: 20px"/>
                                @php else:  @endphp

                                @php endif;  @endphp

                        @php endif;  @endphp</td>
                    @php if(!$attendanceTable->lessonExists($lesson->lesson_id,$sid)):  @endphp
                    <td style ="word-break:break-all;" class="fadedtext" >Class {{ $count }} - End</td>
                        @php endif;  @endphp


                        @php $count++;  @endphp
                    @php endforeach;  @endphp



                </tr>

                @php endforeach;  @endphp

                </tbody>

            </table>


        </div>

    </div>
</div>
<script>
    javascript:window.print();
</script>
</body>

</html>
@endsection
