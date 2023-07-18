 @php foreach($rowset as $row): @endphp

    <div  class="form-group" style="padding-bottom: 10px">
        <input type="hidden" name="lesson_{{ $row->lesson_id }}" value="0"><input type="checkbox" name="lesson_{{ $row->lesson_id }}" value="{{ $row->lesson_id }}" > {{ $row->lesson_name }}        </div>
@php endforeach;  @endphp

