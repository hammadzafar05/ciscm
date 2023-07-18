
<form action="{{ adminUrl(['controller'=>'test','action'=>'editoption','id'=>$id]) }}" method="post">
  @csrf  <div class="form-group">
        <label for="option">{{ __lang('option') }}</label>
        {{ formElement($option) }}
    </div>
    <div class="form-group">
        <label for="is_correct">{{ __lang('is-correct-option') }}</label>
        {{ formElement($select) }}
    </div>
    <button class="btn btn-primary btn-block" type="submit">{{__lang('save')}}</button>
</form>
