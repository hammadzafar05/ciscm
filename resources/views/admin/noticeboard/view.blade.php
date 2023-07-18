
<table class="table table-striped">
    <tbody>


    <tr>
        <th>{{ __lang('title') }}:</th>
        <td>{{ $row->title }}</td>
    </tr>

    <tr>
        <th>{{ __lang('created-by') }}</th>
        <td> {{ $row->admin->user->name }} {{ $row->admin->user->last_name }} ({{ $row->admin->user->email }})

      </tr>

    <tr>
        <th>{{ __lang('instructions') }}:</th>
        <td>{!! $row->instruction !!}</td>
    </tr>
    <tr>
        <th>{{ __lang('passmark') }}</th>
        <td>{{ $row->passmark}}%</td>
    </tr>
    <tr>
        <th>{{ __lang('due-date') }}</th>
        <td>{{ showDate('d/m/Y',$row->due_date) }}</td>
    </tr>
    <tr>
        <th>{{ __lang('created-on') }}</th>
        <td>{{ showDate('d/m/Y',$row->created_at) }}</td>
    </tr>
    <tr>
        <th>{{ __lang('submissions') }}</th>
        <td>{{ $table->getTotalForAssignment($row->id) }}</td>
    </tr>
    <tr>
        <th>
            {{ __lang('average-score') }}
        </th>
        <td>
            {{ $table->getAverageScore($row->id) }}
        </td>
    </tr>
    <tr>
        <th>{{ __lang('total-passed') }}</th>
        <td>{{ $table->getTotalPassed($row->id,$row->passmark) }}</td>
    </tr>
    <tr>
        <th>{{ __lang('total-failed') }}</th>
        <td>{{ $table->getTotalFailedForAssignment($row->id,$row->passmark) }}</td>
    </tr>
    <tr>
        <th>{{ __lang('type') }}</th>
        <td>@php  switch($row->type){
                case 't':
                    echo __lang('text');
                    break;
                case 'f':
                    echo __lang('file-upload');
                    break;
                case 'b':
                    echo __lang('text-file-upload');
                    break;
            }  @endphp</td>
    </tr>
    <tr>
        <th>{{ __lang('send-submission') }}</th>
        <td>{{ boolToString($row->notify) }}</td>
    </tr>
    <tr>
        <th>{{ __lang('allow-late') }}</th>
        <td>{{ boolToString($row->allow_late) }}</td>
    </tr>
    </tbody>
</table>

