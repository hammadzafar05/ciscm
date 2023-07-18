@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.certificate.index')=>__lang('certificates'),
            '#'=>__lang('edit')
        ]])
@endsection

@section('content')
    <form class="form-horizontal" id="editform" method="post" action="{{ adminUrl(['controller'=>'certificate','action'=>'edit','id'=>$row->id]) }}">
@csrf
<div class="card"   ng-app="myApp" ng-controller="myCtrl" >
    <div class="card-header">

        <button type="submit" class="btn btn-primary float-right"><i class="fa fa-save"></i> {{ __lang('save') }} </button>
    </div>
    <div class="card-body">

        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" href="#home" aria-controls="home" role="tab" data-toggle="tab">{{ __lang('design') }}</a></li>
            <li  class="nav-item"><a  class="nav-link" href="#profile" aria-controls="profile" role="tab" data-toggle="tab">{{ __lang('details') }}</a></li>
            <li  class="nav-item"><a class="nav-link" href="#messages" aria-controls="messages" role="tab" data-toggle="tab">{{ __lang('mandatory-classes') }}</a></li>
            <li  class="nav-item"><a class="nav-link" href="#tests" aria-controls="tests" role="tab" data-toggle="tab">{{ __lang('mandatory-tests') }}</a></li>
         <li  class="nav-item"><a class="nav-link" href="#assignments" aria-controls="assignments" role="tab" data-toggle="tab">{{ __lang('mandatory-homework') }}</a></li>

        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="home">
                <a class="btn btn-primary" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                   <i class="fa fa-cogs"></i>  {{ __lang('Options') }}
                </a>

                <a onclick="return confirm('{{ __lang('certificate-reset-warning') }}')" class="btn btn-primary float-right" href="{{ adminUrl(['controller'=>'certificate','action'=>'reset','id'=>$id]) }}"><i class="fa fa-redo"></i> {{ __lang('reset') }}</a>
                <div class="collapse" id="collapseExample">
                    <div class="well">
                        @php $elements = [
                            'student_name','session_name','session_start_date','session_end_date','date_generated','company_name','certificate_number'
                        ];  @endphp

                        <div class="row">
                            @php foreach($elements as $element): @endphp
                                <div class="col-md-2">
                                    <input class="item_control" checked type="checkbox" id="control_{{ $element}}" data-target="box_{{ $element}}" value="{{ $element}}" name="control_{{ $element}}"/> {{ ucfirst(str_replace('_',' ',$element)) }}
                                </div>

                            @php endforeach;  @endphp
                        </div>

                        <br/>
                        <br/>


                        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample1" aria-expanded="false" aria-controls="collapseExample1">
                            <i class="fa fa-calendar"></i> {{ __lang('class-attendance-dates') }}
                        </button>
                        <div class="collapse" id="collapseExample1">
                            <div class="well">
                                <div class="row">
                                    @php foreach($lessons as $lessonRow):  @endphp
                                        <div class="col-md-2">
                                            <input class="item_control" checked type="checkbox" id="control_class_date_{{ $lessonRow->lesson_id.'_'.strtoupper(safeUrl($lessonRow->name)) }}" data-target="box_class_date_{{ $lessonRow->lesson_id.'_'.strtoupper(safeUrl($lessonRow->name)) }}" value="class_date_{{ $lessonRow->lesson_id.'_'.strtoupper(safeUrl($lessonRow->name)) }}" name="control_class_date_{{ $lessonRow->lesson_id.'_'.strtoupper(safeUrl($lessonRow->name)) }}"/> {{ $lessonRow->name }}

                                        </div>
                                    @php endforeach;  @endphp
                                </div>
                            </div>
                        </div>




                        <div >
                            <hr/>

                            <div class="row">
                                <div class="col-md-6 row">
                                    <div class="col-md-8">
                                        <label for="">{{ __lang('set-font-size') }}</label>
                                        <select class="form-control" name="element_selector" id="element_selector">
                                            <option value="">{{ __lang('select-an-element') }}</option>
                                            @php foreach($elements as $element): @endphp
                                                <option value="{{ $element }}">{{ ucfirst(str_replace('_',' ',$element)) }}</option>
                                            @php endforeach;  @endphp
                                            @php foreach($lessons as $lessonRow):  @endphp
                                                <option value="class_date_{{ $lessonRow->lesson_id.'_'.strtoupper(safeUrl($lessonRow->name)) }}">{{ __lang('class-date') }}: {{ $lessonRow->lesson_id.' - '.$lessonRow->name }}</option>
                                            @php endforeach;  @endphp
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">Font Size</label>
                                        <input value="14" placeholder="e.g. 14" type="number" name="font_size" id="font_size" class="form-control number"/>
                                    </div>
                                </div>
                                <div class="col-md-6 row">
                                    <div class="col-md-12">
                                        <label for="">Font Color</label>
                                        <input value="#000"
                                               placeholder="e.g. #000"
                                               type="color"
                                               name="font_color"
                                               id="font_color"
                                               class="form-control"/>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

               <div id="canvas_wrapper" style="overflow: auto">
                   @php if(empty($row->html)):  @endphp
                   <div id="canvas"
                        style=" font-size: 14px; margin:0px auto; position: relative; width: {{ $width }}px; height: {{ $height }}px; overflow: hidden " >
                       <div class="canvas_item"
                            id="box_student_name"
                            style="position: absolute; top: 20px; left: 20px; width: 20px; height: 20px; white-space: nowrap;"
                       >
                           [STUDENT_NAME]
                       </div>
                       {{--style="position: absolute; top: 54%; left: 50%; transform: translate(-54%, -50%); -webkit-transform: translate(-54%, -50%); -moz-transform: translate(-54%, -50%); -o-transform: translate(-54%, -50%); -ms-transform: translate(-54%, -50%);"--}}

                       <div class="canvas_item"
                            id="box_session_name"
                            style=" position: absolute; top: 50px; left: 20px; width: 20px; height: 20px; white-space: nowrap;">
                           [SESSION_NAME]
                       </div>
                       {{----}}

                       <div class="canvas_item" id="box_session_start_date" style=" position: absolute; top: 80px; left: 20px; width: 20px; height: 20px; white-space: nowrap;" >
                           [SESSION_START_DATE]
                       </div>

                       <div class="canvas_item" id="box_session_end_date" style=" position: absolute; top: 110px; left: 20px; width: 20px; height: 20px; white-space: nowrap;" >
                           [SESSION_END_DATE]
                       </div>

                       <div class="canvas_item" id="box_date_generated" style=" position: absolute; top: 140px; left: 20px; width: 20px; height: 20px; white-space: nowrap;" >
                           [DATE_GENERATED]
                       </div>

                       <div class="canvas_item" id="box_company_name" style=" position: absolute; top: 170px; left: 20px; width: 20px; height: 20px; white-space: nowrap;" >
                           [COMPANY_NAME]
                       </div>

                       <div class="canvas_item" id="box_certificate_number" style=" position: absolute; top: 200px; left: 20px; width: 20px; height: 20px; white-space: nowrap;" >
                           [CERTIFICATE_NUMBER]
                       </div>

                       @php $count= 200; foreach($lessons as $lessonRow):  @endphp
                           <div  class="canvas_item" id="box_class_date_{{ $lessonRow->lesson_id.'_'.strtoupper(safeUrl($lessonRow->name)) }}" style="display:none; position: absolute; top: {{ $count }}px; left: 20px; width: 20px; height: 20px; white-space: nowrap;" >
                               [CLASS_DATE_{{ $lessonRow->lesson_id.'_'.strtoupper(safeUrl($lessonRow->name)) }}]
                           </div>
                           @php $count = $count + 30;  @endphp
                       @php endforeach;  @endphp

                       <img src="{{ $siteUrl }}/{{ $row->image }}" style="width: 100%; height: 100%" alt=""/>


                   </div>
                   @php else:  @endphp
                   {!! $row->html !!}
                   @php endif;  @endphp
               </div>
                {{ formElement($form->get('html')) }}
            </div>
            <div role="tabpanel" class="tab-pane" id="profile">


                <div class="card-body">





                    <div class="form-group">
                        {{ formLabel($form->get('name')) }}
                        {{ formElement($form->get('name')) }}   <p class="help-block">{{ formElementErrors($form->get('name')) }}</p>

                    </div>


                    <div class="form-group">
                        {{ formLabel($form->get('course_id')) }}
                        <div>
                        {{ formElement($form->get('course_id')) }}   </div>
                        <div>
                        <p class="help-block">{{ formElementErrors($form->get('course_id')) }}</p>
                        <p class="help-block">{{ __lang('certificate-warning') }}</p>
                        </div>
                    </div>

                    <div class="form-group">
                        {{ formLabel($form->get('description')) }}
                        {{ formElement($form->get('description')) }}   <p class="help-block">{{ formElementErrors($form->get('description')) }}</p>

                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ formLabel($form->get('enabled')) }}
                                {{ formElement($form->get('enabled')) }}   <p class="help-block">{{ formElementErrors($form->get('enabled')) }}</p>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ formLabel($form->get('orientation')) }}
                                {{ formElement($form->get('orientation')) }}   <p class="help-block">{{ formElementErrors($form->get('orientation')) }}</p>
                                <p class="help-block">{{ __lang('certificate-orientation-warning') }}</p>
                            </div>


                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ formLabel($form->get('max_downloads')) }}
                                {{ formElement($form->get('max_downloads')) }}   <p class="help-block">{{ formElementErrors($form->get('max_downloads')) }}</p>
                                <p class="help-block">{{ __lang('max-download-help') }}</p>
                            </div>
                        </div>

                    </div>




                    <div class="form-group" style="margin-bottom:10px">

                        <label for="image" class="control-label">{{ __lang('certificate-image') }}(A4 {{ __lang('size') }} - 595 {{ __lang('pixels') }} x 842 {{ __lang('pixels') }})</label><br />


                        <div class="image"><img data-name="image" src="{{ $display_image }}" alt="" id="thumb" /><br />
                            {{ formElement($form->get('image')) }}
                            <a class="pointer" onclick="image_upload('image', 'thumb');">{{__lang('browse')}}</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class="pointer" onclick="$('#thumb').attr('src', '{{ $no_image }}'); $('#image').attr('value', '');">{{ __lang('clear') }}</a></div>

                    </div>

                </div>

            </div>
            <div role="tabpanel" class="tab-pane" id="messages">

                <div id="classlist" class="option classes">
                    @php foreach($form->getElements() as $element):  @endphp
                        @php if(preg_match('#lesson_#',$element->getName())): @endphp
                        <div  class="form-group" >
                            <input type="checkbox" value="{{ $element->getCheckedValue() }}" name="{{ $element->getName() }}" @if(old($element->getName(),$certificate->lessons()->where('id',$element->getCheckedValue())->first())) checked @endif> {{ $element->getLabel() }}

                        </div>
                         @php endif;  @endphp
                    @php endforeach;  @endphp

                </div>
                <hr/>
                <div  class="form-group" style="padding-bottom: 10px">
                    <input type="hidden" name="any_session" value="0" />
                    <input type="checkbox" name="any_session" value="1" @if(old('any_session',$certificate->any_session)==1) checked @endif>
                      {{ $form->get('any_session')->getLabel() }}
                </div>
            </div>

            <div role="tabpanel" class="tab-pane" id="tests">
                <div class="section-title mt-0">{{ __lang('selected-test') }}</div>

                <div style="max-height: 500px; height: auto">
                <table class="table-stripped table">
                    <thead>
                    <tr>
                        <th>{{ __lang('test') }}</th>
                        <th>{{ __lang('passmark') }}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="test in tests">
                        <td>@{{ test.name }}</td>
                        <td>@{{test.passmark}}%</td>
                        <td><button ng-click="deleteTest(test)" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                            <input type="hidden" name="test_@{{ test.test_id }}" value="@{{test.test_id}}"/></td>
                    </tr>
                    </tbody>
                </table>
                </div>
                <div class="section-title mt-0">{{ __lang('add-tests') }}</div>

                <table id="datatable" class="table table-stripped">
                    <thead>
                    <tr>

                        <th> {{ __lang('test') }}</th>
                        <th  class="no-sort">{{ __lang('created-on') }}</th>
                        <th  class="no-sort">{{ __lang('passmark') }}</th>
                        <th  class="no-sort">{{ __lang('enabled') }}</th>
                        <th  class="no-sort"> </th>
                    </tr>
                    </thead>
                    <tbody id="classes">
                    @php foreach($allTests as $row):  @endphp
                        <tr>

                            <td>{{ $row->name }} </td>
                            <td>{{ showDate('d/M/Y',$row->created_at) }}</td>
                            <td>{{ $row->passmark }}%</td>
                            <td>{{ boolToString($row->enabled) }}</td>
                            <td><button type="button" ng-click="addTest({test_id:'{{ $row->id }}',name:'{{ $row->name }}',passmark:'{{ $row->passmark }}'})" class="btn btn-primary"><i class="fa fa-plus"></i> {{ __lang('add') }}</button></td>
                        </tr>
                    @php endforeach;  @endphp
                    </tbody>

                </table>


            </div>
<div role="tabpanel" class="tab-pane" id="assignments">
    <div class="section-title mt-0">{{ __lang('selected-homework') }}</div>

                <div style="max-height: 500px; height: auto">
                <table class="table-stripped table">
                    <thead>
                    <tr>
                        <th>{{ __lang('homework') }}</th>
                        <th>{{ __lang('passmark') }}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @verbatim
                    <tr ng-repeat="assignment in assignments">
                        <td>{{ assignment.name }}</td>
                        <td>{{assignment.passmark}}%</td>
                        <td><button type="button" ng-click="deleteAssignment(assignment)" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                            <input type="hidden" name="assignment_{{ assignment.assignment_id }}" value="{{assignment.assignment_id}}"/></td>
                    </tr>
                    @endverbatim
                    </tbody>
                </table>
                </div>
    <div class="section-title mt-0">{{ __lang('add-homework') }}</div>

                <table id="datatable" class="table table-stripped">
                    <thead>
                    <tr>

                        <th> {{ __lang('homework') }}</th>
                        <th  class="no-sort">{{ __lang('created-on') }}</th>
                        <th  class="no-sort">{{ __lang('passmark') }}</th>
                        <th  class="no-sort"> </th>
                    </tr>
                    </thead>
                    <tbody id="classes">
                    @php foreach($allAssignments as $row):  @endphp
                        <tr>

                            <td>{{ $row->title }} </td>
                            <td>{{ showDate('d/M/Y',$row->created_at) }}</td>
                            <td>{{ $row->passmark }}%</td>
                            <td><button type="button" ng-click="addAssignment({assignment_id:'{{ $row->id }}',name:'{{ addslashes($row->title) }}',passmark:'{{ $row->passmark }}'})" class="btn btn-primary"><i class="fa fa-plus"></i> {{ __lang('add') }}</button></td>
                        </tr>
                    @php endforeach;  @endphp
                    </tbody>

                </table>


            </div>
        </div>

    </div>
</div>

</form>

<script type="text/javascript">
    $(function(){
        $( ".canvas_item" ).draggable({
            containment: "parent"
        });
    });
</script>
<style>
    .canvas_item{
        cursor: move;
    }
</style>



@endsection



@section('footer')
    <script type="text/javascript" src="{{ asset('client/vendor/ckeditor/ckeditor.js') }}"></script>
    <script type="text/javascript" src="{{ asset('client/js/angular.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('client/app/certificate.js') }}"></script>
    <script type="text/javascript" src="{{ asset('client/vendor/datatables/media/js/jquery.dataTables.min.js') }}"></script>

    <script type="text/javascript">

        CKEDITOR.replace('hcontent', {
            filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
        });

    </script>
    <script type="text/javascript"><!--
        function image_upload(field, thumb) {
            $('#dialog').remove();

            $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="{{ basePath() }}/admin/filemanager?&token=true&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

            $('#dialog').dialog({
                title: '{{addslashes(__lang('Image Manager'))}}',
                close: function (event, ui) {
                    if ($('#' + field).attr('value')) {
                        $.ajax({
                            url: '{{ basePath() }}/admin/filemanager/image?&image=' + encodeURIComponent($('#' + field).val()),
                            dataType: 'text',
                            success: function(data) {
                                $('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
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

        $('select[name=course_id]').change(function(){
            $('#classlist').text('Loading...');
            $('#classlist').load('{{ basePath() }}/admin/certificate/loadclasses/'+$(this).val());
        });

        $('#editform').submit(function(e){
            e.preventDefault();
            var html = $('#canvas_wrapper').html();

            // console.log(html);
            // return false;
            $('#html').val(html);
            $(this).unbind('submit');
            $(this).submit();
        });

        $('#font_size').change(function(){
            console.log('buttoncliced');
            //get the selected element
            var element =  $('#element_selector').val();
            var size= $('#font_size').val();
            if(element.length==0 || size.length==0){
                alert('Please select an element and enter a font size');
            }
            else{
                $('#box_'+element).css('font-size',size+'px');
                console.log('size set');
            }

        });

        $('#font_color').change(function(){
            console.log('font_color button cliked');
            //get the selected element
            var element =  $('#element_selector').val();
            var font_color= $('#font_color').val();
            alert(font_color);
            if(element.length==0 || font_color.length==0){
                alert('Please select an element and enter a font color');
            }
            else{
                $('#box_'+element).css('color',font_color);
                alert('Color set');
            }

        });
        $('#element_selector').change(function(){
            var val= $(this).val();
            if(val.length>0){
                var size = $('#box_'+val).css('font-size');
                size = parseInt(size);
                if(size < 1 ){
                    size =14;
                }
                $('#font_size').val(parseInt(size));
            }
        });

        $('.item_control').click(function(){
            console.log($(this).attr('data-target'));
            $('#'+$(this).attr('data-target')).toggle(this.checked);
        });

        $('.canvas_item').each(function(){
            var isVisible = $(this).is(':visible');
            $('input[data-target='+$(this).attr('id')+']').prop('checked',isVisible);

        })

        //set orientation based on selection
        $('select[name=orientation]').change(function(){
            var val = $(this).val();
            var width,height;
            if(val=='p'){
                width= 595;
                height= 842;
            }
            else{
                height= 595;
                width= 842;
            }
            $('#canvas').css('width',width+'px');
            $('#height').css('height',height+'px');
        });

        var table;
        var dtOptions = {

            "ordering": true,
            columnDefs: [{
                orderable: false,
                targets: "no-sort"
            }]

        };

        table = $('#datatable').DataTable(dtOptions);
        //--></script>
    <script type="text/javascript">
        var app = angular.module('myApp', []);
        app.controller('myCtrl', function($scope,$http) {
            $http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';

            $scope.tests = {
            @php foreach($tests as $row): @endphp
            {{ $row->test_id }}: {test_id:'{{ $row->test_id }}',name:'{{ $row->name }}',passmark:'{{ $row->passmark }}'},
            @php endforeach;  @endphp
        };



            $scope.addTest = function(test){
                $scope.tests[test.test_id]=test;
            }

            $scope.deleteTest = function(test){
                delete  $scope.tests[test.test_id];
            }


            $scope.assignments = {
            @php foreach($assignments as $row): @endphp
            {{ $row->assignment_id }}: {assignment_id:'{{ $row->assignment_id }}',name:'{{ $row->title }}',passmark:'{{ $row->passmark }}'},
            @php endforeach;  @endphp
        };



            $scope.addAssignment = function(assignment){
                $scope.assignments[assignment.assignment_id]=assignment;
            }

            $scope.deleteAssignment = function(assignment){
                console.log($scope.assignments);
                delete  $scope.assignments[assignment.assignment_id];
                return false;
            }

        });

    </script>

@endsection
