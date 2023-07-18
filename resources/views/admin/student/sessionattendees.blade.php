@extends(adminLayout())
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('content')
<table class="table table-hover">
    <thead>
    <tr>
        <th>{{ __lang('id') }}</th>
        <th>{{ __lang('last-name') }}</th>
        <th>{{ __lang('first-name') }}</th>

        <th>{{ __lang('classes-attended') }}</th>
        <th  >{{__lang('actions')}}</th>
    </tr>
    </thead>
    <tbody>
    @php foreach($rowset as $row):  @endphp
        <tr>
            <td><span class="label label-success">{{ $row->student_id }}</span></td>
            <td>{{ $row->last_name }}</td>
            <td>{{ $row->name }}</td>

            <td>{{ $attendanceTable->getTotalForStudent($row->student_id) }}</td>

            <td  >
                <a href="{{ adminUrl(array('controller'=>'student','action'=>'edit','id'=>$row->student_id)) }}" class="btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{__lang('edit')}}"><i class="fa fa-edit"></i></a>
                <a onclick="openPopup('{{ adminUrl(array('controller'=>'student','action'=>'view','id'=>$row->student_id)) }}?noterminal=true')" href="javascript:;" class="btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{__lang('edit')}}"><i class="fa fa-eye"></i></a>

            </td>
        </tr>
    @php endforeach;  @endphp

    </tbody>
</table>

@endsection
