 <table class="table table-stripped">
    <thead>
        <tr>
            <th>{{ __lang('session-course') }}</th>
            <th></th>
        </tr>

    </thead>
    <tbody>
    @php foreach($rowset as $row):  @endphp
    <tr>
        <td>
            {{ $row->course_name }}
        </td>
        <td>
            <a class="btn btn-primary delete-session" href="{{ adminUrl(['controller'=>'download','action'=>'removesession','id'=>$row->id]) }}"><i class="fa fa-trash"></i></a>
        </td>

    </tr>
    @php endforeach;  @endphp
    </tbody>
</table>

