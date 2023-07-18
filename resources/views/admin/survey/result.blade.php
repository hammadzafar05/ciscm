
<table class="table table-stripped">
    <thead>
    <tr>
        <th>{{ __lang('question') }}</th>
        <th>{{ __lang('answer') }}</th>
    </tr>
    </thead>
    <tbody>
    @php foreach($rowset as $row):  @endphp
    <tr>
        <td>{{ $row->question }}</td>
        <td>{{ $row->option}}</td>
    </tr>
    @php endforeach;  @endphp
    </tbody>

</table>
