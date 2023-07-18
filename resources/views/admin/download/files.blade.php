
<table class="table table-stripped">
    <thead>
    <tr>
        <th>
            {{ __lang('file') }}
        </th>
        <th>
            {{ __lang('status') }}
        </th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @php foreach($rowset as $row):  @endphp
    <tr>
        <td><strong>{{  basename($row->path) }}</strong></td>
        <td>{{ (file_exists('usermedia/'.$row->path))? __lang('valid'):__lang('file-missing') }}</td>
        <td><a title="{{ __lang('delete') }}" class="btn btn-primary delete" href="{{ adminUrl(['controller'=>'download','action'=>'removefile','id'=>$row->id]) }}"><i class="fa fa-trash"></i></a>
            <a title="{{ __lang('download') }}" class="btn btn-primary" href="{{ adminUrl(['controller'=>'download','action'=>'download','id'=>$row->id]) }}"><i class="fa fa-download"></i></a>
        </td>
    </tr>
    @php endforeach;  @endphp
    </tbody>

</table>

