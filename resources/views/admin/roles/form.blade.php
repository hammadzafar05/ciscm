<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    <label for="name" class="control-label">@lang('default.name')</label>
    <input class="form-control" name="name" type="text" id="name" value="{{ old('name',isset($role->name) ? $role->name : '') }}" >
    {!! clean( $errors->first('name', '<p class="help-block">:message</p>') ) !!}
</div>

<h3>@lang('default.permissions')</h3>
<div class="" id="accordionExample">

    @foreach(\App\PermissionGroup::orderBy('sort_order')->get() as $group)
    <div class="accordion">
        <div class="accordion-header" id="headingThree{{ $group->id }}" data-toggle="collapse" data-target="#collapseThree{{ $group->id }}" aria-expanded="false" aria-controls="collapseThree{{ $group->id }}">
            <h4 class="mb-0">
                    @lang('default.'.$group->name)
            </h4>
        </div>
        <div id="collapseThree{{ $group->id }}" class="collapse" aria-labelledby="headingThree{{ $group->id }}" data-parent="#accordionExample">
            <div class="accordion-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>@lang('default.permission')</th>
                            <th>@lang('default.enabled')</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($group->permissions as $permission)
                        <tr>
                            <td>@lang('perm.'.$permission->name)</td>
                            <td>
                                <input type="checkbox" name="{{ $permission->id }}" value="{{ $permission->id }}"
                                       @if(isset($role) && $role->permissions()->find($permission->id))
                                        checked
                                           @endif
                                        />
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    @endforeach

</div>
<br/>
<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('default.update') : __('default.create') }}">
</div>
