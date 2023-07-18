<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    <label for="title" class="control-label required">@lang('default.title')</label>
    <input required class="form-control" name="title" type="text" id="title" value="{{ old('title',isset($blogpost->title) ? $blogpost->title : '') }}" >
    {!! clean($errors->first('title', '<p class="help-block">:message</p>')) !!}
</div>
<div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
    <label for="content" class="control-label">@lang('default.content')</label>
     <textarea class="form-control" rows="5" name="content"   id="textcontent" >{!!  old('content',isset($blogpost->content) ? $blogpost->content : '') !!}</textarea>


    {!! clean($errors->first('content', '<p class="help-block">:message</p>')) !!}
</div>
<div class="form-group {{ $errors->has('publish_date') ? 'has-error' : ''}}">
    <label for="publish_date" class="control-label">@lang('default.published-on')</label>
    <input class="form-control date" name="publish_date" type="text" id="publish_date" value="{{ old('publish_date',isset($blogpost->publish_date) ? $blogpost->publish_date : '') }}" >
    {!! clean($errors->first('publish_date', '<p class="help-block">:message</p>')) !!}
</div>
<div class="form-group">
    <label for="categories">@lang('default.categories')</label>
    @if($formMode === 'edit')
        <select multiple name="categories[]" id="categories" class="form-control select2">
            <option></option>
            @foreach(\App\BlogCategory::get() as $category)
                <option  @if( (is_array(old('categories')) && in_array(@$category->id,old('categories')))  || (null === old('categories')  && $blogpost->blogCategories()->where('blog_category_id',$category->id)->first() ))
                    selected
                    @endif
                    value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    @else
        <select  multiple name="categories[]" id="categories" class="form-control select2">
            <option></option>
            @foreach(\App\BlogCategory::get() as $category)
                <option @if(is_array(old('categories')) && in_array(@$category->id,old('categories'))) selected @endif value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    @endif

</div>
<div class="form-group {{ $errors->has('enabled') ? 'has-error' : ''}}">
    <label for="enabled" class="control-label">@lang('default.enabled')</label>
    <select name="enabled" class="form-control" id="enabled" >
    @foreach (json_decode('{"1":"Yes","0":"No"}', true) as $optionKey => $optionValue)
        <option value="{{ $optionKey }}" {{ ((null !== old('enabled',@$blogpost->enabled)) && old('blogpost',@$blogpost->enabled) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
    @endforeach
</select>
    {!! clean($errors->first('enabled', '<p class="help-block">:message</p>')) !!}
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('cover_photo') ? 'has-error' : ''}}">
            <label for="cover_photo" class="control-label">@if($formMode=='edit')@lang('default.change')    @endif @lang('default.cover-image')</label>


            <input class="form-control" name="cover_photo" type="file" id="cover_photo" value="{{ isset($blogpost->cover_photo) ? $blogpost->cover_photo : ''}}" >
            {!! clean($errors->first('cover_photo', '<p class="help-block">:message</p>')) !!}
        </div>

    </div>
    <div class="col-md-6">
        @if($formMode==='edit' && !empty($blogpost->cover_photo))

            <div><img src="{{ asset($blogpost->cover_photo) }}" class="thmaxwidth"/></div> <br/>
            <a onclick="return confirm('@lang('default.delete-prompt')')" class="btn btn-danger" href="{{ route('admin.blog.remove-picture',['id'=>$blogpost->id]) }}"><i class="fa fa-trash"></i> @lang('default.delete') @lang('default.cover-image')</a>

        @endif
    </div>
</div>

<div class="form-group {{ $errors->has('meta_title') ? 'has-error' : ''}}">
    <label for="meta_title" class="control-label">@lang('default.meta-title')</label>
    <input class="form-control" name="meta_title" type="text" id="meta_title" value="{{ old('meta_title',isset($blogpost->meta_title) ? $blogpost->meta_title : '') }}" >
    {!! clean($errors->first('meta_title', '<p class="help-block">:message</p>')) !!}
</div>
<div class="form-group {{ $errors->has('meta_description') ? 'has-error' : ''}}">
    <label for="meta_description" class="control-label">@lang('default.meta-description')</label>
    <textarea class="form-control" rows="5" name="meta_description" type="textarea" id="meta_description" >{{ old('meta_description',isset($blogpost->meta_description) ? $blogpost->meta_description : '') }}</textarea>
    {!! clean($errors->first('meta_description', '<p class="help-block">:message</p>')) !!}
</div>

<div class="form-group {{ $errors->has('show_at_success_stories') ? 'has-error' : ''}}">
    <label for="enabled"
           class="control-label">
        @lang('default.show_at_success_stories')
    </label>
    <select name="show_at_success_stories"
            class="form-control"
            id="show_at_success_stories" >
        @foreach (json_decode('{"0":"No","1":"Yes"}', true) as $optionKey => $optionValue)
            <option value="{{ $optionKey }}"
                    {{ ((null !== old('enabled',@$blogpost->show_at_success_stories)) && old('blogpost',@$blogpost->show_at_success_stories) == $optionKey) ? 'selected' : ''}}>
                {{ $optionValue }}
            </option>
        @endforeach
    </select>
    {!! clean($errors->first('show_at_success_stories', '<p class="help-block">:message</p>')) !!}
</div>

<div class="form-group {{ $errors->has('show_at_accreditation_page') ? 'has-error' : ''}}">
    <label for="enabled"
           class="control-label">
        @lang('default.show_at_accreditation_page')
    </label>
    <select name="show_at_accreditation_page"
            class="form-control"
            id="show_at_accreditation_page" >
        @foreach (json_decode('{"0":"No","1":"Yes"}', true) as $optionKey => $optionValue)
            <option value="{{ $optionKey }}"
                    {{ ((null !== old('enabled',@$blogpost->show_at_accreditation_page)) && old('blogpost',@$blogpost->show_at_accreditation_page) == $optionKey) ? 'selected' : ''}}>
                {{ $optionValue }}
            </option>
        @endforeach
    </select>
    {!! clean($errors->first('show_at_accreditation_page', '<p class="help-block">:message</p>')) !!}
</div>

<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('default.update') : __('default.create') }}">
</div>
