@extends(adminLayout())
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.lesson.index')=>__lang('classes'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('content')
<div>
    <button onclick="image_upload()" id="addFileBtn" class="btn btn-primary"><i class="fa fa-plus"></i> {{ __lang('add-file') }}</button>
    <input id="file_name" type="hidden" name="file_name"/>
    <p><small>{{ __lang('allowed-files') }}: pdf, zip, mp4, mp3, doc, docx, ppt, pptx, xls, xlsx, png, jpeg, gif, txt, csv</small></p>
</div>
<div id="filelist">
<table class="table table-stripped">
    <thead>
    <tr>
        <th>
            {{ __lang('file') }}
        </th>
        <th>
            {{ __lang('status') }}
        </th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @php foreach($rowset as $row):  @endphp
    <tr>
        <td><strong>{{  basename($row->path) }}</strong></td>
        <td>{{ (file_exists('usermedia/'.$row->path))? __lang('valid'):__lang('file_missing') }}</td>
        <td><a title="{{ __lang('delete') }}" class="btn btn-primary delete" href="{{ adminUrl(['controller'=>'lesson','action'=>'removefile','id'=>$row->id]) }}"><i class="fa fa-trash"></i></a>
            <a title="{{ __lang('download') }}" class="btn btn-primary" href="{{ adminUrl(['controller'=>'lesson','action'=>'download','id'=>$row->id]) }}"><i class="fa fa-download"></i></a>
        </td>
    </tr>
    @php endforeach;  @endphp
    </tbody>

</table>
</div>


@endsection

@section('footer')

    <script type="text/javascript">


        function image_upload() {
            var field = 'file_name';
            $('#dialog').remove();

            $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="{{ basePath() }}/admin/filemanager?&token=true&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

            $('#dialog').dialog({
                title: '{{__lang('Select A File')}}',
                close: function (event, ui) {

                    if ($('#' + field).attr('value')) {
                        console.log($('#' + field).attr('value'));

                        $('#filelist').text('Loading...');
                        $.ajax({
                            url: '{{ basePath() }}/admin/lesson/addfile/{{ $id }}?&path=' + encodeURIComponent($('#' + field).val()),
                            dataType: 'text',
                            success: function(data) {
                                //$('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
                                // $('#layout_content').load('{{ basePath() }}/admin/lesson/files/{{ $id }}');
                                $('#layout_content').html(data);
                            }
                        });

                    }

                },
                bgiframe: false,
                width: 800,
                height: 570,
                resizable: true,
                modal: false,
                position: "center"
            });
        };




        $(function(){
            $(document).on('click','.delete',function(e){
                console.log('clicked');
                e.preventDefault();
                $('#layout_content').text('{{__lang('Loading')}}...');
                $('#layout_content').load($(this).attr('href'));
            });

            $(document).on('click','#genmodalinfo a',function(e){
                e.preventDefault();
                $('#genmodalinfo').text('{{__lang('Loading')}}...');
                $('#genmodalinfo').load($(this).attr('href'));
            });



        })

    </script>
@endsection
