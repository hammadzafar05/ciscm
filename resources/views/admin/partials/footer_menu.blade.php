<div class="accordion">
    <div class="accordion-header" id="heading{{ $menu->id }}" data-toggle="collapse" data-target="#collapse{{ $menu->id }}" aria-expanded="false" aria-controls="collapse{{ $menu->id }}">
        <h4>

                {{ $menu->label }}

        </h4>
    </div>
    <div id="collapse{{ $menu->id }}" class="collapse" aria-labelledby="heading{{ $menu->id }}">
        <div class="accordion-body">
            <span class="float-right"><small>{{ $menu->name }}</small></span>
            <form method="post" class="menuform" action="{{ route('admin.menus.update-footer',['footerMenu'=>$menu->id]) }}">
                @csrf
                <div class="form-group">
                    <label for="label">@lang('default.label')</label>
                    <input class="form-control" type="text" name="label" value="{{ $menu->label }}"/>
                </div>
                @if($menu->type=='c')
                    <div class="form-group">
                        <label for="url">URL</label>
                        <input class="form-control" type="text" name="url" value="{{ $menu->url }}"/>
                    </div>
                @endif

                <div class="form-group">
                    <label for="sort_order">@lang('default.sort-order')</label>
                    <input class="form-control number" type="text" name="sort_order" value="{{ $menu->sort_order }}"/>
                </div>

                <div class="form-group">
                    <label for="parent_id">@lang('default.parent')</label>
                    <select class="form-control" name="parent_id" id="parent_id">
                        <option value="0">@lang('default.none')</option>
                        @foreach(\App\FooterMenu::where('parent_id',0)->orderBy('sort_order')->get() as $option)
                            <option @if($menu->parent_id==$option->id) selected @endif value="{{ $option->id }}">{{ $option->label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-check">
                    <input class="form-check-input" name="new_window" type="checkbox" value="1" @if($menu->new_window==1) checked @endif id="new_window{{ $menu->id }}">
                    <label class="form-check-label" for="new_window{{ $menu->id }}">
                        @lang('default.new-window')
                    </label>
                </div>
                <br/>

                <a onclick="return confirm('@lang('default.confirm-delete')')" class="btn btn-danger deletemenu" href="{{ route('admin.menus.delete-footer',['footerMenu'=>$menu->id]) }}">@lang('default.delete')</a>
                <button class="btn btn-primary float-right">@lang('default.save-changes')</button>

            </form>
        </div>
    </div>
</div>
