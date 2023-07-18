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




        <div class="d-md-none d-lg-none">
        @php  foreach($paginator as $row):  @endphp
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $row->certificate_name }}</h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tr>
                            <td>{{  __lang('Document Name')  }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{  $row->certificate_name }}</strong>
                                <p>{!! clean($row->description) !!}</p></td>
                        </tr>
                        <tr>
                            <td>{{ __lang('course-session') }}</td>
                        </tr>
                        <tr>
                            {{--MARUF START--}}
                            @php
                                $course_name = explode(' - Batch ',$row->name);
                                $course_name = @$course_name[0];
                            @endphp
                            <td>{{ $course_name }}</td>
                            {{--MARUF END--}}
                        </tr>
                        <tr>
                            <td>{{  __lang('classes-required')  }}</td>
                        </tr>
                        <tr>
                            <td>@php  $tl= $clTable->getTotalForCertificate($row->certificate_id); echo (empty($tl))? __lang('None'):$tl;  @endphp</td>
                        </tr>
                        <tr>
                            <td>{{  __lang('Tests Required')  }}</td>
                        </tr>
                        <tr>
                            <td>@php  $tt= $ctTable->getTotalForCertificate($row->certificate_id); echo (empty($tt))? __lang('None'):$tt;  @endphp</td>
                        </tr>
                        <tr>
                            <td> @php  if(false): @endphp
                                    <a  onclick="return window.open('{{  route('student.student.certificate',['id'=>$row->certificate_id]) }}', '_blank', 'toolbar=no,scrollbars=yes,resizable=yes,top=100,left=100,width={{  ($row->orientation=='l')? '862':'615'  }},height={{  ($row->orientation=='l')? '615':'882'  }}')"  href="#" class="btn btn-primary " ><i class="fa fa-eye"></i> {{  __lang('View')  }}</a>
                                @php  endif;  @endphp
                                <a href="{{  route('student.student.downloadcertificate',['id'=>$row->certificate_id]) }}" class="btn btn-primary " ><i class="fa fa-download"></i> {{  __lang('Download')  }}</a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        @php  endforeach;  @endphp
    </div>
        <div class="card d-none d-sm-none d-md-block">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>{{  __lang('Document Name')  }}</th>
                        <th>{{ __lang('course-session') }}</th>
                        <th>{{  __lang('Classes Required')  }}</th>
                        <th>{{  __lang('Tests Required')  }}</th>
                        <th class="text-right1" >{{  __lang('Actions')  }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php  foreach($paginator as $row):  @endphp
                    <tr>

                        <td class="pt-3"><h5>{{  $row->certificate_name }}</h5>
                            <p>{!! $row->description !!}   </p>
                        </td>
                        {{--MARUF START--}}
                        @php
                            $course_name = explode(' - Batch ',$row->name);
                            $course_name = @$course_name[0];
                        @endphp
                        <td>{{ $course_name }}</td>
                        {{--MARUF END--}}
                        <td>@php  $tl= $clTable->getTotalForCertificate($row->certificate_id); echo (empty($tl))? __lang('None'):$tl;  @endphp</td>
                        <td>@php  $tt= $ctTable->getTotalForCertificate($row->certificate_id); echo (empty($tt))? __lang('None'):$tt;  @endphp</td>
                        <td class="text-right1">
                            @php  if(false): @endphp
                            <a  onclick="return window.open('{{  $this->url('application/certificate',['id'=>$row->certificate_id]) }}', '_blank', 'toolbar=no,scrollbars=yes,resizable=yes,top=100,left=100,width={{  ($row->orientation=='l')? '862':'615'  }},height={{  ($row->orientation=='l')? '615':'882'  }}')"  href="#" class="btn btn-primary " ><i class="fa fa-eye"></i> {{  __lang('View')  }}</a>
                            @php  endif;  @endphp
                            <a href="{{  route('student.student.downloadcertificate',['id'=>$row->certificate_id]) }}" class="btn btn-primary " ><i class="fa fa-download"></i> {{  __lang('Download')  }}</a>

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
                    route('student.student.certificates')

                );
            @endphp
        </div>
        </div>




<!--container ends-->
<script>
    function openPop(url,width,height){
        window.open(url, "_blank", "toolbar=no,scrollbars=yes,resizable=yes,top=100,left=100,width="+width+",height="+height+"");
        return false;
    }
</script>
@endsection
