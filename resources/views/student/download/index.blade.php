@extends('layouts.student')
@section('pageTitle',$pageTitle)
@section('innerTitle',$pageTitle)
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('student.dashboard')=>__lang('dashboard'),
            '#'=>$pageTitle
        ]])
@endsection

@section('content')



<!--breadcrumb-section ends-->
<!--container starts-->
<div class="container" style="background-color: white; min-height: 400px;   padding-bottom:50px; margin-bottom: 10px;   " >
    <!--primary starts-->

    <div class="card-body">


        <div class="table-responsive">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>{{  __lang('id')  }}</th>
                <th>{{  __lang('name')  }}</th>
                <th>{{ __lang('course') }}</th>
                <th>{{  __lang('files')  }}</th>
                 <th ></th>
            </tr>
            </thead>
            <tbody>
            @php  foreach($paginator as $row):  @endphp

            <tr>
                 <td><span class="label label-success">{{  $row->download_id  }}</span></td>
                        <td>{{  $row->download_name }}</td>
                <td>{{ $row->course_name }}</td>
                        <td>{{  $fileTable->getTotalForDownload($row->download_id) }}</td>

                        <td class="text-right">
                        @php  if ($fileTable->getTotalForDownload($row->download_id)> 0):  @endphp
                            <a href="{{  route('student.download.files',array('id'=>$row->download_id)) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="View Files"><i class="fa fa-eye"></i> {{  __lang('view-files')  }}</a>
                            <a href="{{  route('student.download.allfiles',array('id'=>$row->download_id)) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="Download All Files"><i class="fa fa-download"></i> {{  __lang('Download All')  }}</a>
                        @php  else: @endphp
                            <strong>{{  __lang('no-files-available')  }}</strong>
                        @php  endif;  @endphp
                        </td>
                    </tr>

            @php  endforeach;  @endphp

            </tbody>
        </table>
</div>
        @php
        // add at the end of the file after the table
        echo paginationControl(
        // the paginator object
            $paginator,
            // the scrolling style
            'sliding',
            // the partial to use to render the control
            null,
            // the route to link to when a user clicks a control link
            route('student.download.index')
        );
         @endphp
    </div>


</div>

<!--container ends-->

@endsection
