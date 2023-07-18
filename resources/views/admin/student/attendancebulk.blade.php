@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('footer')
    <script type="text/javascript" src="{{ asset('client/js/angular.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('client/app/attendance.js') }}"></script>
@endsection


@section('content')
<div  ng-app="myApp" ng-controller="myCtrl"  >

    <div >
        <div class="card">
            <div class="card-header">
                <header><h4  >{{ __lang('select') }} <strong>{{ __lang('students') }}</strong></h4></header>
            </div>
            <div class="card-body">

                <img ng-if="showLoading" src="{{ basePath() }}/img/ajax-loader.gif">
                <div class="row mb-5">
                    <div class="col-md-5">{{ formElement($course_id) }}</div>
                    <div class="col-md-5">{{ formElement($lesson_id) }}</div>
                    <div class="col-md-2"><button ng-click="saveAttendance('{{ basePath() }}')" ng-disabled="!course_id || !lesson_id" type="button" class="btn btn-primary btn-rounded"><i class="fa fa-save"></i> {{__lang('save')}}</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">

                        <table class="table table-stripped">
                            <thead>
                            <tr>
                                <th>{{ __lang('first-name') }}</th>
                                <th>{{ __lang('last-name') }}</th>
                                <th>{{ __lang('email') }}</th>
                                <th class="text-right1" style="width:130px">{{ __lang('mark') }}</th>
                            </tr>
                            </thead>
                            <tr  ng-repeat="student in students" >
                                <td>@{{student.first_name}}</td>
                                <td>@{{student.last_name}}</td>
                                <td>@{{student.email}}</td>
                                <td><input type="checkbox" value="1"  ng-model="answers[student.student_id]" ng-change="studentChecked(student.student_id,student)" /></td>
                            </tr>
                        </table>



                    </div>



                </div>





            </div>
        </div><!--end .box -->
    </div><!--end .col-lg-12 -->
</div>


<script>
    var basePath = '{{ basePath() }}';
</script>
@endsection
