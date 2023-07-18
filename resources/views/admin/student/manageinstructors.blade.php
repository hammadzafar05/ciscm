
<form id="manageform" action="{{ adminUrl(['controller'=>'student','action'=>'manageinstructors','course'=>$course->id,'lesson'=>$lesson->id]) }}" method="post" >
@csrf
    <div style="max-height: 400px; overflow: auto">

<table id="datatable" class="table table-stripped">
    <thead>
    <tr>
        <th></th>
        <th>{{ __lang('name') }}</th>
        <th>{{ __lang('role') }}</th>
    </tr>
    </thead>
    <tbody>
    @php foreach($rowset as $row): @endphp
    <tr>
        <td><input @php if($table->accountExists($lesson->id,$course->id,$row->id)): @endphp checked="checked" @php endif;  @endphp name="{{ $row->id }}" value="{{ $row->id }}" type="checkbox"/></td>
        <td>{{ $row->user_name }}</td>
        <td>{{ $row->admin_role_name }}</td>
    </tr>
    @php endforeach;  @endphp
    </tbody>
</table>

</div>
<button type="button" id="savebtn" class="btn btn-primary float-right">{{__lang('save')}}</button>
</form>
<div style="clear: both"></div>

