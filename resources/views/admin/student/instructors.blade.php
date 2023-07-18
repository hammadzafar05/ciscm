@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.student.sessions')=>__('default.courses'),
            '#'=>__lang('manage-instructors')
        ]])
@endsection

@section('content')
<div class="card">

    <div class="card-body" >
        <table class="table table-stripped">
            <thead>
            <tr>
                <th>{{ __lang('class') }}</th>
            <th>{{ __lang('instructors') }}</th>
            <th></th>

            </tr>
            </thead>
            @php foreach($rowset as $row): @endphp
                <tr>
                    <td>{{ $row->name }}</td>
                    <td>

                        <div class="btn-group dropup">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ $table->getTotalInstructors($row->lesson_id,$id) }}
                            </button>
                            <div class="dropdown-menu">
                                @foreach($table->getInstructors($row->lesson_id,$id) as $row2)
                                <a class="dropdown-item" href="#">{{ $row2->name }}</a>
                                @endforeach
                            </div>
                        </div>


                    </td>
                    <td><a class="btn btn-primary" onclick="openModal('{{__lang('instructors-for')}} {{ $row->name }}','{{ route('admin.student.manageinstructors',['course'=>$row->course_id,'lesson'=>$row->lesson_id]) }}')" href="#">{{ __lang('manage-instructors') }}</a></td>
                </tr>


            @php endforeach;  @endphp

        </table>
    </div>

</div>


@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('client/vendor/datatables/media/css/jquery.dataTables.min.css') }}">
@endsection

@section('footer')

    <script type="text/javascript" src="{{ asset('client/vendor/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script>
        var table;
        var dtOptions = {

            "ordering": true,
            "paging": false

        };


        $( document ).ajaxComplete(function() {
            table = $('#datatable').DataTable(dtOptions);
        });
        $(function(){

            $(document).on('click','#savebtn',function(e){
                e.preventDefault();
                table.destroy();
                $('#manageform').submit();
            });




        });

    </script>


@endsection
