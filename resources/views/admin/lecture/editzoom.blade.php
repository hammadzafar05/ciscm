
<form method="post" class="form" action="{{ adminUrl(['controller'=>'lecture','action'=>'editzoom','id'=>$id]) }}">
@csrf
    <div>

        <div class="form-group">
            <label for="">{{ __lang('title') }}</label>
            <input name="title" class="form-control"   required="required" value="{{$data['title'] }}" type="text">
        </div>
        <div class="form-group">
            <label for="">{{ __lang('meeting-id') }}</label>
            <input value="{{$data['meeting_id']}}" required="required" class="form-control" type="text" name="meeting_id" placeholder="{{ __lang('zoom-placeholder') }}"/>

        </div>


        <div class="form-group">
            <label for="">{{ __lang('meeting-password') }}</label>
            <input required="required" class="form-control" type="text" name="password" value="{{$data['password']}}" />

        </div>

        <div class="form-group">
            <label for="">{{ __lang('instructions') }} ({{ __lang('optional') }})</label>
            <textarea class="form-control" name="instructions"  >{{$data['instructions']}}</textarea>

        </div>


        <div class="form-group">
            <label for="sort_order">{{ __lang('sort-order') }} ({{ __lang('optional') }})</label>
            <input name="sort_order"   class="form-control number" placeholder="{{ __lang('digits-only') }}" value="{{$data['sort_order'] }}" type="text">   <p class="help-block"></p>

        </div>

    </div>
        <button type="submit" class="btn btn-primary">{{ __lang('save-changes') }}</button>

</form>


