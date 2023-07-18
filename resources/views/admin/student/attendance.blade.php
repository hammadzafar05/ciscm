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


                <div class="row">
                    <div class="col-md-5">
                       <div> <input  autocomplete="off" ng-keyup="loadStudents('{{ basePath() }}')"  ng-model="filter" type="text" name="filter" class="form-control" placeholder="{{ __lang('attendance-select-help') }}"/></div>
                        <div style="border: solid 1px #cccccc; height: 300px; overflow: auto;">
                            <table class="table table-striped">
                                <tr style="cursor:pointer;" ng-repeat="student in students" ng-click="add(student)">
                                    <td>@{{student.first_name}} @{{student.last_name}} - @{{student.email}}</td>
                                </tr>

                                </table>



                        </div>
                        <br/>
                    </div>
                    <div class="col-md-1">
                        <img ng-if="showLoading" src="{{ basePath() }}/img/ajax-loader.gif">
                        </div>

                    <div class="col-md-6">

                        <div style="border: solid 1px #cccccc; height: 300px; overflow: auto;">
                            <table class="table table-striped">
                                <tr style="cursor:pointer;" ng-repeat="student in selected_students">
                                    <td>@{{student.first_name}} @{{student.last_name}} - @{{student.email}}<button ng-click="remove(student)" class="float-right btn  btn-xs btn-primary btn-equal"><i class="fa fa-minus-circle"></i></button></td>
                                </tr>

                            </table>


                        </div>
                        <div style="padding-top: 10px" id="sessionbox">{{ formElement($course_id) }}</div>
                        <div style="padding-top: 10px" id="lessonbox">{{ formElement($lesson_id) }}</div>
                        <br/>
                        <div class="form-footer ">
                            <button ng-click="saveAttendance('{{ basePath() }}')" ng-disabled="!course_id || !lesson_id" type="button" class="btn btn-block btn-primary">{{ __lang('submit') }}</button>
                        </div>

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
