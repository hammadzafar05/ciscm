@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>$customCrumbs])
@endsection

@section('content')



<div class="card">
    <div class="card-header">
        <h2>{{ $row->course_name }}</h2>
    </div>
    <div class="card-body">


        <div class="" role="tabpanel" data-example-id="togglable-tabs">

            <table class="table table-stripped">
                <thead>
                <tr>
                    <th>{{ __lang('class') }}</th>
                    <th>{{ __lang('date') }}</th>
                    <th>{{ __lang('action') }}</th>
                </tr>
                </thead>
                @php foreach($totalLessonsDetails as $row):  @endphp
                <tr>
                    <td>{{ htmlentities( $row->name) }}</td>
                    <td>{{ htmlentities( showDate('d/M/Y',$row->lesson_date)) }}</td>
                    <td>


                        <div id="tutorial-{{ $row->lesson_id }}">
		                    @php
		                    $str_like = "Full";
		                    if($lesson_status[$row->lesson_id] == 'Limited') {
			                    $str_like = "Limited";
		                    }
		                    @endphp
                            <div class="btn-likes">
                                <button title="Delete"
                                        onClick="addLikes({{ $row->lesson_id }},{{ $row->course_id }},{{ $student_id }},'<?php echo $str_like; ?>')"
                                        href=""
                                        class="btn btn-xs btn-primary btn-equal <?php echo $str_like; ?>"
                                        data-lesson_id="{{ $row->lesson_id }}"
                                        data-course_id="{{ $row->course_id }}"
                                        data-student_id="{{ $student_id }}">
	                                <?php echo ucwords($str_like); ?>
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
                @php endforeach;  @endphp
            </table>
        </div>

    </div>
</div>
<script>
    function addLikes(lesson_id,course_id,student_id,action) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            dataType: "json",
            url: '{{ route('admin.session.limit_lesson_access') }}',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: "lesson_id=" + lesson_id + "&course_id=" + course_id + "&student_id="+student_id+"&action="+action,
            beforeSend: function(){
                $('#tutorial-'+lesson_id+' .btn-likes').html("<i class='fa fa-2x fa-spin fa-spinner'></i>");
            },
            success: function(data){
                var likes = parseInt($('#likes-'+lesson_id).val());
                switch(action) {
                    case "Full":
                        //$('#tutorial-'+lesson_id+' .btn-likes').html('<input type="button" title="Limited" class="Limited" onClick="addLikes('+lesson_id+',\'Limited\')" />');
                        $('#tutorial-'+lesson_id+' .btn-likes').html('<button title="Access" onClick="addLikes('+lesson_id+','+course_id+','+student_id+',\'Limited\')" ' +
                            'href="" class="btn btn-xs btn-primary btn-equal Limited" data-lesson_id="'+lesson_id+'" data-course_id="'+course_id+'" data-student_id="'+student_id+'">Limited</button>');
                        break;
                    case "Limited":
                        //$('#tutorial-'+lesson_id+' .btn-likes').html('<input type="button" title="Full" class="Full"  onClick="addLikes('+lesson_id+',\'Full\')" />');
                        $('#tutorial-'+lesson_id+' .btn-likes').html('<button title="Access" onClick="addLikes('+lesson_id+','+course_id+','+student_id+',\'Full\')" ' +
                            'href="" class="btn btn-xs btn-primary btn-equal Full" data-lesson_id="'+lesson_id+'" data-course_id="'+course_id+'" data-student_id="'+student_id+'">Full</button>');
                        break;
                }
            }
        });
    }
</script>

@endsection
