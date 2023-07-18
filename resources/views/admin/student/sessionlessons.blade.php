
@php foreach($lessons as $row):  @endphp
    <div class="form-group" style="padding-bottom: 10px">
        <label for="session_id">{{ __lang('class') }} {{ $row->lesson_id}}: {{ $row->name}} </label>
        <input type="text" name="lesson_{{ $row->lesson_id}}" class="date form-control"/>
    </div>
@php endforeach;  @endphp
