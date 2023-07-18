<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    <label for="title" class="control-label required">@lang('default.title')</label>
    <input required class="form-control" name="title" type="text" id="title" value="{{ old('title',isset($article->title) ? $article->title : '') }}" >
    {!! clean( $errors->first('title', '<p class="help-block">:message</p>') ) !!}
</div>

<div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
    <label for="content" class="control-label">@lang('default.content')</label>
    <textarea class="form-control" rows="5" name="content" type="textarea" id="textcontent" >{!! old('content',isset($article->content) ? $article->content : '') !!}</textarea>
    {!! clean( $errors->first('content', '<p class="help-block">:message</p>') ) !!}
</div>

<div class="form-group {{ $errors->has('slug') ? 'has-error' : ''}}">
    <label for="slug" class="control-label">@lang('default.slug')</label>
    <input class="form-control" name="slug" type="text" id="slug" value="{{ old('slug',isset($article->slug) ? $article->slug : '') }}" >
    {!! clean( $errors->first('slug', '<p class="help-block">:message</p>'))  !!}
</div>

<div class="form-group {{ $errors->has('meta_title') ? 'has-error' : ''}}">
    <label for="meta_title" class="control-label">@lang('default.meta-title')</label>
    <input class="form-control" name="meta_title" type="text" id="meta_title" value="{{ old('meta_title',isset($article->meta_title) ? $article->meta_title : '') }}" >
    {!! clean( $errors->first('meta_title', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('meta_description') ? 'has-error' : ''}}">
    <label for="meta_description" class="control-label">@lang('default.meta-description')</label>
    <textarea class="form-control" rows="5" name="meta_description" type="textarea" id="meta_description" >{{ old('meta_description',isset($article->meta_description) ? $article->meta_description : '') }}</textarea>
    {!! clean(  $errors->first('meta_description', '<p class="help-block">:message</p>')) !!}
</div>

<div class="form-group {{ $errors->has('enabled') ? 'has-error' : ''}}">
    <label for="enabled" class="control-label">@lang('default.enabled')</label>
    <select name="enabled" class="form-control" id="enabled" >
        @foreach (json_decode('{"1":"Yes","0":"No"}', true) as $optionKey => $optionValue)
            <option value="{{ $optionKey }}" {{ ((null !== old('enabled',@$article->enabled)) && old('article',@$article->enabled) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
        @endforeach
    </select>
    {!! clean($errors->first('enabled', '<p class="help-block">:message</p>') ) !!}
</div>

<div class="form-group">
    <div class="form-check form-check-inline">
        <input type="hidden" name="mobile" value="0">
        <input @if(old('mobile',isset($article->mobile) ? $article->mobile : '')==1) checked @endif class="form-check-input" type="checkbox" id="mobile" value="1" name="mobile">
        <label class="form-check-label" for="mobile">{{ __lang('mobile') }}</label>
    </div>
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('default.update') : __('default.create') }}">
</div>
