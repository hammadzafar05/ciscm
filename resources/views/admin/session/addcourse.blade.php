@extends('layouts.admin')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.student.sessions')=>__('default.courses'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <h2><small>{{ __lang('setup-course') }}</small></h2>

                <div class="clearfix"></div>
            </div>
            <div class="card-body">

                <form id="sessionform"
                      class="form-horizontal"
                      role="form"
                      method="post"
                      action="{{ $action  }}">
                @csrf
                <!-- Smart Wizard -->
                    <p>{!! __lang('fill-each-section') !!} </p>
                    <div id="course_wizard" class="form_wizard wizard_horizontal">
                        <ul class="wizard_steps">
                            <li>
                                <a href="#step-1">
                                    <span class="step_no">1</span>
                                    <span class="step_descr">
                                              {{ __lang('info') }}<br/>
                                              <small>{{ __lang('basic-course-data') }}</small>
                                          </span>
                                </a>
                            </li>
                            <li>
                                <a href="#step-2">
                                    <span class="step_no">2</span>
                                    <span class="step_descr">
                                              {{ __lang('options') }}<br/>
                                              <small>{{ __lang('course-options') }}</small>
                                          </span>
                                </a>
                            </li>
                            <li>
                                <a href="#step-3">
                                    <span class="step_no">3</span>
                                    <span class="step_descr">
                                              {{ __lang('scheduling') }}<br/>
                                              <small>{{ __lang('start-end-dates') }}</small>
                                          </span>
                                </a>
                            </li>
                            <li>
                                <a href="#step-4">
                                    <span class="step_no">4</span>
                                    <span class="step_descr">
                                              {{ __lang('instructors') }}<br/>
                                              <small>{{ __lang('course-instructors') }}</small>
                                          </span>
                                </a>
                            </li>
                        </ul>
                        <div id="step-1">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div>
                                            <label for="password1" class="control-label"><span class="required">*</span>{{ __lang('course-name') }}</label>
                                        </div>
                                        <div>
                                            {{ formElement($form->get('session_name')) }}
                                            <p class="help-block">{{ formElementErrors($form->get('session_name')) }}</p>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div>
                                            <label for="password1" class="control-label"><span class="required">*</span>{{ __lang('short-description') }}</label>
                                        </div>
                                        <div>
                                            {{ formElement($form->get('short_description')) }}
                                            <p class="help-block">{{ formElementErrors($form->get('short_description')) }}</p>
                                            <div>
                                                <span class="chars-remaining"></span> {{ __lang('characters-remaining') }}
                                                (<span class="chars-max"></span> {{strtolower(__lang('maximum'))}})
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div>
                                            <label for="password1" class="control-label">{{ __lang('course-description') }}</label>
                                        </div>
                                        <div>
                                            {{ formElement($form->get('description'))  }}
                                            <p class="help-block">{{ formElementErrors($form->get('description')) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div>
                                            <label for="password1" class="control-label">{{ __lang('introduction') }}</label>
                                        </div>
                                        <div>
                                            {{ formElement($form->get('introduction'))  }}
                                            <p class="help-block">{{ formElementErrors($form->get('introduction')) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div>
                                            <label for="outcomes" class="control-label">{{ __lang('outcomes') }}</label>
                                        </div>
                                        <div>
                                            {{ formElement($form->get('outcomes'))  }}
                                            <p class="help-block">{{ formElementErrors($form->get('outcomes')) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div>
                                            <label for="grading_system"
                                                   class="control-label">
                                                {{ __lang('Grade System') }} <i>(Note: Only for Transcript)</i>
                                            </label>
                                        </div>
                                        <div>
                                            {{ formElement($form->get('grading_system'))  }}
                                            <p class="help-block">{{ formElementErrors($form->get('grading_system')) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div>
                                            <label for="module_description"
                                                   class="control-label">
                                                {{ __lang('module_description') }}
                                            </label>
                                        </div>
                                        <div>
                                            {{ formElement($form->get('module_description'))  }}
                                            <p class="help-block">{{ formElementErrors($form->get('module_description')) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                              <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div>
                                            <label for="module_length"
                                                   class="control-label">
                                                Module Length
                                            </label>
                                        </div>
                                        <div>
                                            {{ formElement($form->get('module_length'))  }}
                                            <p class="help-block">{{ formElementErrors($form->get('module_length')) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div>
                                            <label for="course_objective"
                                                   class="control-label">
                                                Course Objective
                                            </label>
                                        </div>
                                        <div>
                                            {{ formElement($form->get('course_objective'))  }}
                                            <p class="help-block">{{ formElementErrors($form->get('course_objective')) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div>
                                            <label for="accreditation"
                                                   class="control-label">
                                                Accreditation
                                            </label>
                                        </div>
                                        <div>
                                            {{ formElement($form->get('accreditation'))  }}
                                            <p class="help-block">{{ formElementErrors($form->get('accreditation')) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div>
                                            <label for="student_feedback"
                                                   class="control-label">
                                                Student Feedback
                                            </label>
                                        </div>
                                        <div>
                                            {{ formElement($form->get('student_feedback'))  }}
                                            <p class="help-block">{{ formElementErrors($form->get('student_feedback')) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div>
                                            <label for="course_faq"
                                                   class="control-label">
                                                FAQ
                                            </label>
                                        </div>
                                        <div>
                                            {{ formElement($form->get('course_faq'))  }}
                                            <p class="help-block">{{ formElementErrors($form->get('course_faq')) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div>
                                            <label for="course_details"
                                                   class="control-label">
                                                Details
                                            </label>
                                        </div>
                                        <div>
                                            {{ formElement($form->get('course_details'))  }}
                                            <p class="help-block">{{ formElementErrors($form->get('course_details')) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" style="margin-bottom:10px">
                                        <div class="col-lg-4 col-md-4">
                                            <label for="image"
                                                   class="control-label">
                                                {{ __lang('cover-image') }} ({{ __lang('optional') }})
                                            </label>
                                            <br/>
                                        </div>
                                        <div>
                                            <div class="image">
                                                <img data-name="image"
                                                     src="{{ $display_image }}"
                                                     alt=""
                                                     id="thumb"/>
                                                <br/>
                                                {{ formElement($form->get('picture')) }}
                                                <a class="pointer"
                                                   href="#"
                                                   onclick="image_upload('image', 'thumb');">
                                                    {{ __lang('browse') }}</a>&nbsp;&nbsp;
                                                    |&nbsp;&nbsp;
                                                <a href="javascript:;"
                                                   class="pointer"
                                                   onclick="$('#thumb').attr('src', '{{ $no_image }}'); $('#image').attr('value', '');">
                                                    {{ __lang('clear') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="step-2" class="container">


                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div>
                                            <label for="password1" class="control-label">{{ __lang('payment-required') }}</label>
                                        </div>
                                        <div>
                                            {{ formElement($form->get('payment_required')) }}
                                            <p class="help-block">{{ formElementErrors($form->get('payment_required')) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div>
                                            <!--change by kabir -->
                                            <label for="password1" class="control-label">Special Fee</label>
                                        </div>
                                        <div>
                                            {{ formElement($form->get('amount')) }}
                                            <p class="help-block">{{ formElementErrors($form->get('amount')) }}</p>
                                        </div>
                                    </div>
                                </div>
                                  <div class="col-md-3">
                                    <div class="form-group">
                                        <div>
                                            <!--change by kabir -->
                                        <label for="module_length"
                                                   class="control-label">
                                                regular_fee
                                            </label>
                                        </div>
                                        <div>
                                            {{ formElement($form->get('regular_fee')) }}
                                            <p class="help-block">{{ formElementErrors($form->get('regular_fee')) }}</p>
                                        </div>
                                    </div>
                                </div>
                              
                                <div class="col-md-3" style="display: none">
                                    <div class="form-group">
                                        <div>
                                            <label for="password1" class="control-label">{{ __lang('emi_available') }}</label>
                                        </div>
                                        <div>
                                            {{ formElement($form->get('emi_status')) }}
                                            <p class="help-block">{{ formElementErrors($form->get('emi_status')) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3" style="display: none">
                                    <div class="form-group">
                                        <div>
                                            <label for="password1" class="control-label">{{ __lang('emi_installment') }}</label>
                                        </div>
                                        <div>
                                            {{ formElement($form->get('emi_installment')) }}
                                            <p class="help-block">{{ formElementErrors($form->get('emi_installment')) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div>
                                            <label for="password1" class="control-label">{{ __lang('effort') }}</label>
                                        </div>
                                        <div>
                                            {{ formElement($form->get('effort')) }}
                                            <p class="help-block">{{ formElementErrors($form->get('effort')) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div>
                                            <label for="password1" class="control-label">{{ __lang('length') }}</label>
                                        </div>
                                        <div>
                                            {{ formElement($form->get('length')) }}
                                            <p class="help-block">{{ formElementErrors($form->get('length')) }}</p>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">

                                        <label for="password1" class="control-label">{{ __lang('course-categories') }} ({{ __lang('optional') }})</label>

                                        <div>


                                            {{ formElement($form->get('session_category_id[]')) }}
                                            <p class="help-block">{{ formElementErrors($form->get('session_category_id[]')) }}</p>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div>
                                            <label for="password1" class="control-label">{{ __lang('status') }}</label>
                                        </div>
                                        <div>
                                            {{ formElement($form->get('session_status')) }}
                                            <p class="help-block">{{ formElementErrors($form->get('session_status')) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div>
                                            <label for="enable_discussion" class="control-label">{{ __lang('enable-discussions') }}</label>
                                        </div>
                                        <div>
                                            {{ formElement($form->get('enable_discussion')) }}
                                            <p class="help-block">{{ formElementErrors($form->get('enable_discussion')) }}</p>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div>
                                            <label for="password1" class="control-label">{{ formLabel($form->get('enable_chat')) }}</label>
                                        </div>
                                        <div>
                                            {{ formElement($form->get('enable_chat')) }}
                                            <p class="help-block">{{ formElementErrors($form->get('enable_chat')) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div>
                                            <label for="enable_discussion" class="control-label">{{ formLabel($form->get('enforce_order')) }}</label>
                                        </div>
                                        <div>
                                            {{ formElement($form->get('enforce_order')) }}
                                            <p class="help-block">{{ formElementErrors($form->get('enforce_order')) }}</p>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <div class="row">


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div>
                                            <label for="enable_discussion" class="control-label">{{ __lang('enable-forum') }}</label>
                                        </div>
                                        <div>
                                            {{ formElement($form->get('enable_forum')) }}
                                            <p class="help-block">{{ formElementErrors($form->get('enable_forum')) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div>
                                            <label for="enable_discussion" class="control-label">{{ __lang('capacity') }}</label>
                                        </div>
                                        <div>
                                            {{ formElement($form->get('capacity')) }}
                                            <p class="help-block">{{ formElementErrors($form->get('capacity')) }}</p>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div>
                                            <label for="enable_discussion" class="control-label">{{ __lang('enforce-capacity') }}</label>
                                        </div>
                                        <div>
                                            {{ formElement($form->get('enforce_capacity')) }}
                                            <p class="help-block">{{ formElementErrors($form->get('enforce_capacity')) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div id="step-3">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div>
                                            <label for="password1" class="control-label">{{ __lang('course-start-date') }} ({{ __lang('optional') }})</label>
                                        </div>
                                        <div>
                                            {{ formElement($form->get('session_date')) }}
                                            <p class="help-block">{{ formElementErrors($form->get('session_date')) }}</p>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div>
                                            <label for="password1" class="control-label">{{ __lang('course-end-date') }} ({{ __lang('optional') }})</label>
                                        </div>
                                        <div>
                                            {{ formElement($form->get('session_end_date')) }}
                                            <p class="help-block">{{ formElementErrors($form->get('session_end_date')) }}</p>
                                        </div>
                                    </div>
                                </div>


                            </div>

                            <div class="row">


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div>
                                            <label for="password1" class="control-label">{{ __lang('enrollment-closes') }} ({{ __lang('optional') }})</label>
                                        </div>
                                        <div>
                                            {{ formElement($form->get('enrollment_closes')) }}
                                            <p class="help-block">{{ formElementErrors($form->get('enrollment_closes')) }}</p>
                                        </div>
                                    </div>
                                </div>


                            </div>


                        </div>
                        <div id="step-4">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">

                                        <label for="password1" class="control-label">{{ __lang('course-instructors') }} ({{ __lang('optional') }})</label>

                                        {{ formElement($form->get('session_instructor_id[]')) }}
                                        <p class="help-block">{{ formElementErrors($form->get('session_instructor_id[]')) }}</p>

                                    </div>
                                </div>

                            </div>


                        </div>

                    </div>
                    <!-- End SmartWizard Content -->


                </form>


            </div>
        </div>
    </div>

@endsection

@section('footer')
    <script type="text/javascript" src="{{ basePath() . '/client/vendor/ckeditor/ckeditor.js' }}"></script>
    <script type="text/javascript" src="{{ basePath().'/client/vendor/datatables/media/js/jquery.dataTables.min.js' }}"></script>
    <script type="text/javascript" src="{{ basePath().'/client/vendor/stupidtable/stupidtable.min.js' }}"></script>

    <!-- FastClick -->
    <script src="{{ basePath() }}/client/themes/cpanel/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="{{ basePath() }}/client/themes/cpanel/vendors/nprogress/nprogress.js"></script>
    <!-- jQuery Smart Wizard -->
    <script src="{{ basePath() }}/client/themes/cpanel/vendors/jQuery-Smart-Wizard/js/jquery.smartWizard.js"></script>

    <script>
        $('#course_wizard').smartWizard({
            labelNext: '{{__lang('Next')}}', // label for Next button
            labelPrevious: '{{__lang('Previous')}}', // label for Previous button
            labelFinish: '{{__lang('Finish')}}'  // label for Finish button
        });
        CKEDITOR.on('instanceReady', function () {
            $('#course_wizard').smartWizard('fixHeight');
        });

    </script>


    <script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/picker.js"></script>
    <script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/picker.date.js"></script>
    <script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/picker.time.js"></script>
    <script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/legacy.js"></script>
    <script type="text/javascript"><!--
        function sortTable() {
            console.log('table sorted');

            $("#selectedTable").stupidtable();
            var $th_to_sort = $("#selectedTable").find("thead th").eq(1);
            $th_to_sort.stupidsort();

            $th_to_sort.stupidsort('asc');
        }


        var table;

        function initializeTable() {

            var dtOptions = {

                "ordering": true,
                columnDefs: [{
                    orderable: false,
                    targets: "no-sort"
                },
                    {
                        "targets": [2],
                        "visible": false
                    }
                ]

            };

            if (!$.fn.dataTable.isDataTable('#datatable')) {
                table = $('#datatable').DataTable(dtOptions);
            }
            $('.dataTables_filter input').attr("placeholder", "Class or Group name");
            showList();
            jQuery(function () {
                $('.date').pickadate({
                    format: 'yyyy-mm-dd'
                });
                table.$('.date').pickadate({
                    format: 'yyyy-mm-dd'
                });

                table.$('.time').pickatime({
                    interval: 15
                });
            });

            $("#select_all").change(function () {  //"select all" change
                var status = this.checked; // "select all" checked status
                table.$('.cbox').each(function () { //iterate all listed checkbox items
                    this.checked = status; //change ".checkbox" checked status
                });
                initializeTable(table);
                showList();

            });


            function showList() {
                $('#selectedlist').text('');
                table.$('.cbox').each(function () { //iterate all listed checkbox items
                    if (this.checked) {
                        var id = $(this).val();
                        //$('#selectedlist').prepend('<li>'+table.$('#lesson_name_'+id).text()+' (position : '+table.$('input[name=sort_order_'+id+']').val()+')</li>');
                        $('#selectedlist').append('<tr class="sort_row" data-id="' + id + '"><td>' + table.$('#lesson_name_' + id).text() + '</td><td class="sort_cell">' + table.$('input[name=sort_order_' + id + ']').val() + '</td><td>' + table.$('input[name=lesson_date_' + id + ']').val() + '</td></tr>');
                    } //change ".checkbox" checked status
                });

                sortTable();
            }


            table.$('input').change(function () { //".checkbox" change

                showList();
            });

            table.$('.cbox').change(function () { //".checkbox" change
                //uncheck "select all", if one of the listed checkbox item is unchecked
                if (this.checked == false) { //if this item is unchecked
                    $("#select_all")[0].checked = false; //change "select all" checked status to false
                }

                //check "select all" if all checkbox items are checked
                if (table.$('.cbox:checked').length == table.$('.cbox').length) {
                    $("#select_all")[0].checked = true; //change "select all" checked status to true
                }
                showList();
            });
        }


        initializeTable();

        //--></script>


    <script type="text/javascript">

        CKEDITOR.replace('description', {
            filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
        });

        CKEDITOR.replace('introduction', {
            filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
        });

        CKEDITOR.replace('outcomes', {
            filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
        });

        CKEDITOR.replace('grading_system', {
            filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
        });
        CKEDITOR.replace('module_description', {
            filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
        });

        CKEDITOR.replace('accreditation', {
            filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
        });

        CKEDITOR.replace('course_objective', {
            filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
        });

        CKEDITOR.replace('student_feedback', {
            filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
        });


        CKEDITOR.replace('course_faq', {
            filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
        });

        CKEDITOR.replace('course_details', {
            filebrowserBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserImageBrowseUrl: '{{ basePath() }}/admin/filemanager',
            filebrowserFlashBrowseUrl: '{{ basePath() }}/admin/filemanager'
        });

    </script>


    <script>


        function image_upload(field, thumb) {
            console.log('image upload');
            $('#dialog').remove();

            $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="{{ basePath() }}/admin/filemanager?&token=true&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

            $('#dialog').dialog({
                title: '{{ addslashes(__lang('Image Manager')) }}',
                close: function (event, ui) {
                    if ($('#' + field).attr('value')) {
                        $.ajax({
                            url: '{{ basePath() }}/admin/filemanager/image?&image=' + encodeURIComponent($('#' + field).val()),
                            dataType: 'text',
                            success: function (data) {
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

        $(function () {

            //     $('textarea[name=short_description]').maxlength({max: 300,feedbackText:'{r} {{__lang('characters-remaining')}} ({m} {{__lang('maximum')}})'});
            $('textarea[name=short_description]').maxLength();
            $('#sessionform').submit(function (e) {
                e.preventDefault();
                table.destroy();
                $(this).unbind('submit');
                $(this).submit();
            });

            $('#addClassForm').submit(function (e) {
                $('#addClassBtn').text('{{__lang('saving')}}...');
                e.preventDefault();
                $.post($(this).attr('action'), $(this).serialize(), function (data) {
                    table.destroy();
                    $("#classes").prepend(data);
                    $('#addClassBtn').text('{{__lang('Save Changes')}}');
                    $('#addClassModal').modal('hide');
                    $('#addClassForm').find("input[type=text], textarea").val("");
                    jQuery('.date').pickadate({
                        format: 'yyyy-mm-dd'
                    });

                    jQuery('.time').pickatime({
                        interval: 15
                    });

                    //table= $('#datatable').DataTable(dtOptions);
                    initializeTable();
                });
            })

            $("#selectedTable tbody").sortable({
                opacity: 0.6, update: function () {

                    var counter = 1;
                    //console.log(order);

                    $('.sort_row').each(function () {
                        $(this).find('.sort_cell').text(counter);
                        //update field
                        var id = $(this).attr('data-id');
                        $('input[name=sort_order_' + id + ']').val(counter);
                        counter++;
                    })
                }
            });

        });

        $('div.actionBar a.buttonNext,div.actionBar a.buttonPrevious').on('click', function () {
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#course_wizard").offset().top
            }, 200);
        });
    </script>
    <style>
        #selectedTable tr {
            cursor: grabbing;
        }
    </style>
@endsection

@section('header')
    <link rel="stylesheet" href="{{ basePath().'/client/vendor/pickadate/themes/default.date.css' }}">
    <link rel="stylesheet" href="{{ basePath().'/client/vendor/pickadate/themes/default.time.css' }}">
    <link rel="stylesheet" href="{{ basePath().'/client/vendor/pickadate/themes/default.css' }}">
    <link rel="stylesheet" href="{{ basePath().'/client/vendor/datatables/media/css/jquery.dataTables.min.css' }}">

    <link href="{{ asset('client/css/wizard.css') }}" rel="stylesheet">
    <style>
        span.required {
            color: red;
            font-weight: bold;
        }
    </style>
    <script type="text/javascript" src="{{ asset('client/vendor/limit-characters-maxlength/jquery.maxlength.min.js') }}"></script>
@endsection
