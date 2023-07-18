
<div class="row_">
    <div >
        <div class="box_">

                    <form id="filterform"  role="form"  method="get" action="{{ adminUrl(array('controller'=>'session','action'=>'browseclasses','id'=>$sessionId)) }}?type={{@$_GET['type']}}">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="sr-only" for="filter">{{ __lang('filter') }}</label>
                                    {{ formElement($text) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="sr-only" for="group">{{ __lang('class-group') }}</label>
                                    {{ formElement($select) }}
                                </div>

                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">{{ __lang('filter') }}</button>
                                <button type="button" onclick="$('#filterform input, #filterform select').val(''); $('#filterform').submit();" class="btn  btn-danger">{{ __lang('Clear') }}</button>

                            </div>
                        </div>




                    </form>


            <div class="box-body_">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>{{ __lang('id') }}</th>
                        <th>{{ __lang('name') }}</th>
                        <th>{{ __lang('class-type') }}</th>
                        @php if(GLOBAL_ACCESS): @endphp
                            <th>{{ __lang('created-by') }}</th>
                        @php endif;  @endphp
                        <th class="text-right1" >{{__lang('actions')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php foreach($paginator as $row):  @endphp
                        <tr>
                            <td><span class="label label-success">{{ $row->id }}</span></td>
                            <td>{{ $row->name }} @php if($row->type=='c'): @endphp(<a target="_blank" style="text-decoration: underline" href="{{ adminUrl(array('controller'=>'lecture','action'=>'index','id'=>$row->id)) }}">{{ $lectureTable->getTotalLectures($row->id) }} {{ __lang('lectures') }}</a>)  @php endif;  @endphp</td>


                            <td>{{ ($row->type=='c')?__lang('online'):__lang('physical-location') }}</td>
                            @php if(GLOBAL_ACCESS): @endphp
                                <td>{{ adminName($row->admin_id) }}</td>
                            @php endif;  @endphp

                            <td class="text-right1">

                                <a class="btn btn-primary" href="{{adminUrl(['controller'=>'session','action'=>'setclass','id'=>$row->id])}}?sessionId={{$sessionId}}"><i class="fa fa-plus"></i> {{ __lang('select') }}</a>





                            </td>
                        </tr>
                    @php endforeach;  @endphp

                    </tbody>
                </table>
<div id="pagerlinks">
{{ $paginator->links() }}

</div>
            </div><!--end .box-body -->
        </div><!--end .box -->
    </div><!--end .col-lg-12 -->
</div>


<!-- START SIMPLE MODAL MARKUP --><!-- /.modal -->
<!-- END SIMPLE MODAL MARKUP -->
