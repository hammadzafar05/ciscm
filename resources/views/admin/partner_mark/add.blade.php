@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            url('/admin/partner_mark/index')=>__lang('mark-management'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection


@section('content')
    <div ng-app="myApp" ng-controller="myCtrl">
        <div>
            <div class="card">

                <div class="card-body">

                    <form method="post" action="{{ adminUrl(array('controller'=>'partnermark','action'=>$action,'id'=>$id)) }}">
                        @csrf
                        <div class="form-group">
                            <label for="filter">Course</label>
                            <select id="course_id"
                                    name="course_id"
                                    class="form-control required"
                                    required
                                    onchange="show_mark_distribution_table()">
                                <option value="">Select an option</option>
                                @foreach($sessions as $session)
                                    <option value="{{ $session->id }}">{{ $session->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="search_result">

                        </div>

                        <div class="form-footer marks_form" style="display: none;">
                            <button type="submit" class="btn btn-primary">{{ __lang('save-changes') }}</button>
                        </div>
                    </form>
                </div>
            </div><!--end .box -->
        </div><!--end .col-lg-12 -->
    </div>

@endsection

@section('footer')
    <script type="text/javascript">

        var show_mark_distribution_table = function (){
            var course_id = $('#course_id').val();
            if(course_id == ''){
                $('.marks_form').hide();
                $("#search_result").html('');
            }else{
                $.ajax({
                    url: './course/students/'+course_id,
                    dataType: 'html',
                    success: function(data) {
                        $("#search_result").html(data);
                        $('.marks_form').show();
                    }
                });
            }
        }

        var basePath = '{{ basePath() }}';
    </script>

@endsection
