@extends(adminLayout())
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('content')
<div   id="video-library" >
    <div >
        <div class="card_">
            <div class="card-header_">

                <form id="filterform"   role="form"  method="get" action="{{ adminUrl(array('controller'=>'lecture','action'=>'library','id'=>$lectureId)) }}">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="sr-only" for="filter">{{ __lang('filter') }}</label>
                                {{ formElement($text) }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="sr-only" for="group">{{ __lang('sort') }}</label>
                                {{ formElement($sortSelect) }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">{{__lang('filter')}}</button>
                            <button type="button" onclick="$('#filterform input, #filterform select').val(''); $('#filterform').submit();" class="btn  btn-danger">{{__lang('clear')}}</button>

                        </div>
                        <div class="col-md-2">
                            <a target="_blank" onclick="$('#generalLargeModal').modal('hide')" class="btn btn-primary float-right" href="{{ adminUrl(array('controller'=>'video','action'=>'add')) }}"><i class="fa fa-plus"></i> {{ __lang('add-videos') }}</a>

                        </div>
                    </div>





                </form>

            </div>
            <div class="card-body_ pt-2">

                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>{{ __lang('id') }}</th>
                        <th>{{ __lang('thumbnail') }}</th>
                        <th>{{ __lang('name') }}</th>
                        <th> {{ __lang('length') }}</th>
                        <th>{{ __lang('size') }}</th>
                        <th>{{ __lang('added-on') }}</th>
                        @if(GLOBAL_ACCESS)
                            <th>{{ __lang('created-by') }}</th>
                        @endif
                        <th class="text-right1" >{{__lang('actions')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php foreach($paginator as $row):  @endphp
                        <tr>
                            <td><span class="label label-success">{{ $row->id }}</span></td>
                            <td class="pt-1">
                                @php $thumb = 'uservideo/'.$row->id.'/'.fileName($row->file_name).'.jpg'; $video = 'uservideo/'.$row->id.'/'.$row->file_name;  @endphp
                                @if(file_exists($thumb) && file_exists($video))
                                    <img class="img-thumbnail" style="max-width: 100px" src="{{basePath()}}/uservideo/{{$row->id}}/{{fileName($row->file_name)}}.jpg?rand={{time()}}" alt="{{$row->name}}" />
                                @endif
                            </td>
                            <td>{{ $row->name }}</td>
                            <td>
                                @if(!empty($row->length))
                                    {{$row->length}}
                                @endif
                            </td>
                            <td>
                                @if(!empty($row->file_size))
                                {{formatSizeUnits($row->file_size)}}
                                @endif
                            </td>
                            <td>
                                {{showDate('d/m/Y',$row->created_at)}}
                            </td>

                            @if(GLOBAL_ACCESS)
                                <td>{{ adminName($row->admin_id) }}</td>
                            @endif

                            <td class="text-right1">
                                @if(file_exists($video))
                                    <a class="btn btn-success"  href="{{adminUrl(['controller'=>'video','action'=>'play','id'=>$row->id])}}" target="_blank" ><i class="fa fa-play"></i> {{__lang('play')}}</a>
                                @endif
                                <a class="btn btn-primary"  href="{{ adminUrl(array('controller'=>'lecture','action'=>'addvideolibrary','id'=>$row->id)) }}?lecture={{$lectureId}}"    ><i class="fa fa-plus"></i> {{ __lang('select') }}</a>
                            </td>
                        </tr>
                    @php endforeach;  @endphp

                    </tbody>
                </table>
<div id="pagerlinks">
    @php
    // add at the end of the file after the table
    echo paginationControl(
    // the paginator object
        $paginator,
        // the scrolling style
        'sliding',
        // the partial to use to render the control
        null,
        // the route to link to when a user clicks a control link
        array(
            'route' => 'admin/default',
            'controller'=>'lecture',
            'action'=>'library',
            'id'=>$lectureId,
            'filter'=>$filter,
            'sort'=>$sort
        )
    );
    @endphp
</div>

            </div><!--end .box-body -->
        </div><!--end .box -->
    </div><!--end .col-lg-12 -->
</div>

@endsection
