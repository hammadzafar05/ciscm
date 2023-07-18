
<form class="form" method="post" action="{{ adminUrl(array('controller'=>'student','action'=>'enroll','id'=>$id)) }}">
  @csrf
    <h5>{{ __lang('Enroll') }} {{ $student->name.' '.$student->last_name  }}</h5>
    <div style="padding-bottom: 10px">
        {{ formElement($select) }}

    </div>
    <button class="btn btn-primary" type="submit">{{ __lang('enroll') }}</button>
</form>


<script>
    $(".select2insidemodal").select2({
        dropdownParent: $("#generalModal")
    });
</script>

