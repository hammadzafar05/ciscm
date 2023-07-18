@extends('layouts.student')
@section('pageTitle',$pageTitle)
@section('innerTitle',$pageTitle)
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('student.dashboard')=>__lang('dashboard'),
            route('student.download.index')=>__lang('downloads'),
            '#'=>__lang('files')
        ]])
@endsection

@section('content')

<div class="card">
<div class="card-header">
    <a href="{{  route('student.download.allfiles',array('id'=>$id)) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="Download All Files"><i class="fa fa-download"></i> {{ __lang('Download All') }}</a>

</div>
    <div class="card-body">
<p>
    {!! $row->description !!}
</p>

        <table class="table table-stripped">
            <thead>
            <tr>
                <th>
                    {{  __lang('file')  }}
                </th>
                <th>
                    {{  __lang('status')  }}
                </th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @php  foreach($rowset as $row):  @endphp
                <tr>
                    <td><strong>{{   basename($row->path) }}</strong></td>
                    <td>{{  (file_exists('usermedia/'.$row->path))? __lang('valid'):__lang('file-missing')  }}</td>
                    <td>
                        <a title="Download" class="btn btn-primary" href="{{  route('student.download.file',['id'=>$row->id])  }}"><i class="fa fa-download"></i> {{  __lang('download')  }}</a>
                    </td>
                </tr>
            @php  endforeach;  @endphp
            </tbody>

        </table>
    </div>

</div>

<!--container ends-->

@endsection
