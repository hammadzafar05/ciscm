@extends('layouts.student')
@section('pageTitle','')
@section('innerTitle','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('student.dashboard')=>__lang('dashboard'),
            '#'=>$pageTitle
        ]])
@endsection

@section('content')
<!DOCTYPE html><html  {{ langMeta() }}>

<head>
    <title>{{  __lang('Certificate')  }}: {{  $row->certificate_name  }}</title>
    <!-- BEGIN META -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="your,keywords">
    <meta name="description" content="Certufucate">
    <!-- END META -->

    <!-- Bootstrap -->
    <link href="{{  url('/') }}/themes/cpanel/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{  url('/') }}/themes/cpanel/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="{{  url('/') }}/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.css" />


    <!-- END STYLESHEETS -->

    <style>
        .fadedtext{
            font-size: 8px;
            color: #d9d9d9;
        }
    </style>


</head>


<body>
<div >
    {{  $html }}
</div>

</body>

</html>
@endsection
