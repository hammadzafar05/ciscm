<!DOCTYPE html>
<html {{langMeta()}}>

<head>
    <title>{{__lang('Transcript')}}</title>
    <!-- BEGIN META -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="your,keywords">
    <meta name="description" content="Short explanation about this website">
    <!-- END META -->

    <style>
        * {
            font-family: DejaVu Sans, sans-serif;
        }
    </style>

    <!-- END STYLESHEETS -->
    @php $logo = setting('image_logo'); @endphp
    <style>
        .fadedtext {
            font-size: 8px;
            color: #d9d9d9;
        }

        .table {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }
        .module_description table {
            font-size: 12px;
            width: 100%;
            cellspacing: 0px;
            border-spacing: 0 !important;
            border-collapse: collapse;
            padding: 0px !important;
        }
        .module_description table td {
            vertical-align: middle;
            text-align: center;
            border-collapse: collapse;
            padding: 0px !important;
        }
        table td{
            padding: 0px !important;
            margin: 0px !important;
        }

        .grading table {
            font-size: 12px;
            cellspacing: 0px;
            border-spacing: 0 !important;
            border-collapse: collapse;
            padding: 0px !important;
        }
        .grading table td {
            border-collapse: collapse;
            padding: 0px !important;
        }

        .table td, .table th {
            border: 0px solid #ddd;
        }


        .table th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #4CAF50;
            color: white;
        }

        .watermark { position: relative; }
        .watermark::after { position: absolute; bottom: 0; right: 0; content: "COPYRIGHT"; }
    </style>


</head>


<body>
<div class="container" style="background-image: url("{{ asset($logo) }}")">
    <table class="table" style="border: 0px">
        <tr>
            <td align="center">
                {{--<img src="https://i.imgur.com/LKexbxE.png" width="50px">--}}
                <span  style="font-size: 16px;font-weight: bold;">{{ setting('general_site_name') }}</span>
                <br>
                <span  style="font-size: 12px;">{{ setting('general_website_domain') }}</span>
            </td>
        </tr>

        <tr>
            <td align="center" height="40px">

            </td>
        </tr>
        <tr>
            <td align="center">
                @php
                    $course_name = explode(' - Batch ',$session->name);
                    $course_name = @$course_name[0];
                @endphp
                <span style="font-size: 14px;font-weight: bold;">
                    {{ $course_name }}
                </span>
            </td>
        </tr>
        <tr>
            <td align="center">
                <u  style="font-size: 15px;font-weight: bold;">Result Card</u>
            </td>
        </tr>
        <tr>
            <td align="center" height="20px">

            </td>
        </tr>
    </table>
    <table class="table" style="border: 0px">
        <tr>
            <td>
                <table class="table" style="border: 0px">
                    <tr>
                        <td align="left" style="font-size: 12px;">
                            Name of Student: <b>{{ $student->user->name }} {{ $student->user->last_name }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="font-size: 12px;">
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="font-size: 12px;">
                            @php
                                $reg_year = date('y-m',strtotime($student->user->created_at));
                                $reg_number = 'WA-'.$reg_year.'-'.str_pad($student->user->id,4,"0",STR_PAD_LEFT);
                            @endphp
                            Registration No: {{ $reg_number }}
                        </td>
                    </tr>

                    <tr>
                        <td align="left" style="font-size: 12px;">
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="font-size: 12px;">
                            Certificate No: {{ $certificate_tracking_number }}
                        </td>
                    </tr>

                    <tr>
                        <td align="left" style="font-size: 12px;">
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="font-size: 12px;">
                            Passing Year: {{ $passing_year }}
                        </td>
                    </tr>

                    <tr>
                        <td align="left" style="font-size: 12px;">
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="font-size: 12px;">
                            Name of Centre: {{ setting('general_site_name') }}
                        </td>
                    </tr>

                    <tr>
                        <td align="left" style="font-size: 12px;">
                            <br>
                        </td>
                    </tr>
                </table>
            <td>
                <table class="table">
                    <tr>
                        <td align="center" style="font-size: 12px;border: 1px;">
                            Marks
                        </td>
                        <td align="center" style="font-size: 12px;border: 1px;">
                            Grade
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="font-size: 12px;border: 1px;">
                            &lt;50%
                        </td>
                        <td align="center" style="font-size: 12px;border: 1px;">
                            F
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="font-size: 12px;border: 1px;">
                            50%
                        </td>
                        <td align="center" style="font-size: 12px;border: 1px;">
                            C+
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="font-size: 12px;border: 1px;">
                            55%
                        </td>
                        <td align="center" style="font-size: 12px;border: 1px;">
                            B-
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="font-size: 12px;border: 1px;">
                            60%
                        </td>
                        <td align="center" style="font-size: 12px;border: 1px;">
                            B
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="font-size: 12px;border: 1px;">
                            65%
                        </td>
                        <td align="center" style="font-size: 12px;border: 1px;">
                            B+
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="font-size: 12px;border: 1px;">
                            70%
                        </td>
                        <td align="center" style="font-size: 12px;border: 1px;">
                            A-
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="font-size: 12px;border: 1px;">
                            75%
                        </td>
                        <td align="center" style="font-size: 12px;border: 1px;">
                            A
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="font-size: 12px;border: 1px;">
                            80%
                        </td>
                        <td align="center" style="font-size: 12px;border: 1px;">
                            A+
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    @if($session->module_description != '')
        <br>
        <div class="module_description">
            {!! $session->module_description !!}
        </div>
    @endif

    @if(($result_cgpa != '') && ($result_grade != ''))
    <div class="grading">
        <br>
        <table class="table" style="border: 0px">
            <tr>
                <td align="center" colspan="2">
                    <b>Achievement</b>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2">
                    <br>
                </td>
            </tr>
            <tr>
                <td align="center">
                    Grade: <b>{!! $result_grade !!}</b>
                </td>
                <td align="center">
                    GPA: <b>{{ strip_tags($result_cgpa) }}</b>
                </td>
            </tr>
        </table>
    </div>
    @endif
    {{--<br>
    <br>
    <table class="table" style="border: 0px">
        <tr>
            <td align="center" style="font-size: 12px;">
                Verification Through {{ setting('general_website_domain') }}
            </td>
        </tr>
    </table>--}}
</div>



</body>
</html>

