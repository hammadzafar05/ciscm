
<form action="{{ adminUrl(['controller'=>'survey','action'=>'editoption','id'=>$id]) }}" method="post">
 @csrf   <div class="form-group">
        <label for="option">{{ __lang('option') }}</label>
        {{ formElement($option) }}
    </div>
    <button class="btn btn-primary btn-block" type="submit">{{__lang('save')}}</button>
</form>
