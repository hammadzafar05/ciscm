@extends('layouts.student')
@section('pageTitle',$course->name.': '.$classRow->name)
@section('innerTitle',$course->name)
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>$customCrumbs])
@endsection

@section('content')
    <div class="card profile-widget  mt-5">
        <div class="profile-widget-header">

            @if(!empty($classRow->picture))
                <img  class="rounded-circle profile-widget-picture" src="{{  resizeImage($classRow->picture,400,300,url('/')) }}" >
            @else
                <img  class="rounded-circle profile-widget-picture"  src="{{ asset('img/course.png') }}" >
            @endif
            <div class="profile-widget-items">
                <div class="profile-widget-item">
                    <div class="profile-widget-item-value  pt-2 pb-1">{{ $classRow->name }}</div>
                </div>
                <div class="profile-widget-item">
                    @php  if($previous):  @endphp
                    <a class="btn btn-primary btn-lg" href="{{  $previous  }}"><i class="fa fa-chevron-circle-left"></i> {{  __lang('previous')  }}</a>
                    @php  endif;  @endphp

                    @php  if($next):  @endphp
                    <a class="btn btn-primary btn-lg " href="{{  $next  }}">{{  __lang('start-class')  }} <i class="fa fa-chevron-circle-right"></i></a>
                    @php  endif;  @endphp
                </div>
            </div>
        </div>
        <div class="profile-widget-description">
            <!-- Nav tabs -->
            <ul class="nav nav-pills" role="tablist">
                <li class="nav-item"><a  class="nav-link active"  href="#home" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-info-circle"></i> {{  __lang('introduction')  }}</a></li>
                <li class="nav-item"><a  class="nav-link"  href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-table"></i> {{  __lang('table-of-contents')  }}</a></li>
                <li class="nav-item"><a   class="nav-link"  href="#resources" aria-controls="resources" role="tab" data-toggle="tab"><i class="fa fa-download"></i> {{  __lang('resources')  }}</a></li>

            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="home">

                    <div class="panel panel-default">
                        <div class="panel-body">
                            <p>{!! $classRow->introduction !!}    </p>
                        </div>
                        <div class="panel-footer" style="min-height: 65px">
                            @php  if($previous):  @endphp
                            <a class="btn btn-primary btn-lg" href="{{  $previous  }}"><i class="fa fa-chevron-circle-left"></i> {{  __lang('previous')  }}</a>
                            @php  endif;  @endphp

                            @php  if($next):  @endphp
                            <a class="btn btn-primary btn-lg float-right" href="{{  $next  }}">{{  __lang('start-class')  }} <i class="fa fa-chevron-circle-right"></i></a>
                            @php  endif;  @endphp
                        </div>
                    </div>

                </div>
                <div role="tabpanel" class="tab-pane" id="profile">
                    @php  $count=1; foreach($lectures as $row):  @endphp

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            {{  $count.'. '.$row->title  }}
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>{{  __lang('content')  }}</th>
                                    <th>{{  __lang('type')  }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php  foreach($lecturePageTable->getPaginatedRecords(false,$row->id) as $page):  @endphp
                                <tr>
                                    <td>{{  $page->title  }}</td>
                                    <td>@php
                                            switch($page->type){
                                                case 't':
                                                    echo __lang('text');
                                                    break;
                                                case 'v':
                                                    echo  __lang('video');
                                                    break;
                                                case 'c':
                                                    echo __lang('html-code');
                                                    break;
                                                case 'i':
                                                    echo __lang('image');
                                                    break;
                                                case 'q':
                                                    echo __lang('quiz');
                                                    break;
                                                case 'l':
                                                    echo  __lang('video');
                                                    break;
                                            }  @endphp</td>
                                </tr>
                                @php  endforeach;  @endphp
                                </tbody>
                            </table>
                        </div>
                        <div class="panel-footer" style="min-height: 65px">
                            <a class="btn btn-primary btn-lg float-right" href="{{  route('student.course.lecture',['course'=>$sessionId,'lecture'=>$row->id])  }}">{{  __lang('start-lecture')  }} <i class="fa fa-chevron-right"></i></a>
                        </div>
                    </div>
                    @php  $count++;  @endphp
                    @php  endforeach;  @endphp
                </div>
                <div role="tabpanel" class="tab-pane" id="resources">
                    @php  if($downloads->count() > 0): @endphp
                    <a href="{{  route('student.course.allclassfiles',array('id'=>$classRow->id,'course'=>$sessionId)) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="{{  __lang('download-all')  }} {{  __lang('files')  }}"><i class="fa fa-download"></i> {{  __lang('download-all')  }}</a>
                    @php  endif;  @endphp
                    <table class="table table-hover mt-2">
                        <thead>
                        <tr>
                            <th>{{  __lang('file')  }}</th>
                            <th ></th>
                        </tr>
                        </thead>
                        <tbody>
                        @php  foreach($downloads as $download):  @endphp
                        <td>{{  basename($download->path) }}</td>

                        <td class="text-right">
                            @php  if ($fileTable->getTotalForDownload($classRow->id)> 0):  @endphp
                            <a href="{{  route('student.course.classfile',array('id'=>$download->id,'course'=>$sessionId)) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="{{  __lang('download')  }} {{  __lang('file')  }}"><i class="fa fa-download"></i> {{  __lang('download')  }}</a>

                            @php  else: @endphp
                            <strong>{{  __lang('no-files-available')  }}</strong>
                            @php  endif;  @endphp
                        </td>
                        </tr>

                        @php  endforeach;  @endphp

                        </tbody>
                    </table>

                </div>
                <div role="tabpanel" class="tab-pane" id="progress">

                </div>
                @php  if(!empty($sessionRow->enable_discussion)): @endphp
                <div role="tabpanel" class="tab-pane" id="discuss">
                    <form class="form" method="post" action="{{  $this->url('application/default',['controller'=>'student','action'=>'adddiscussion']) }}">
                        @csrf
                        <p>{{  __lang('ask-a-question')  }}</p>
                        <div class="modal-body">

                            <div class="form-group">
                                <label>{{ __lang('Recipients') }}</label>
                                <select name="admin_id[]" class="form-control select2" data-options="required:true" required="required" multiple="multiple"><option value=""></option>
                                    <option value="admins">{{  __lang('administrators')  }}</option>
                                    @php  foreach($instructors as $instructor): @endphp
                                    <option value="{{  $instructor->admn_id  }}">{{  $instructor->name.' '.$instructor->last_name }}</option>
                                    @php  endforeach;  @endphp

                                </select>
                            </div>

                            <input type="hidden" name="session_id" value="{{  $sessionId  }}"/>
                            <div class="form-group">
                                {{  formLabel($form->get('subject')) }}
                                {{  formElement($form->get('subject')) }}   <p class="help-block">{{  formElementErrors($form->get('subject')) }}</p>

                            </div>




                            <div class="form-group">
                                {{  formLabel($form->get('question')) }}
                                {{  formElement($form->get('question')) }}   <p class="help-block">{{  formElementErrors($form->get('question')) }}</p>

                            </div>

                            <button type="submit" class="btn btn-primary">{{  __lang('submit')  }}</button>

                        </div>

                    </form>
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 20px">
                            <h2>{{  __lang('your-questions')  }}</h2>
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>{{  __lang('subject')  }}</th>
                                    <th>{{  __lang('created-on')  }}</th>
                                    <th>{{  __lang('recipients')  }}</th>
                                    <th>{{  __lang('replied')  }}</th>
                                    <th class="text-right1" style="width:90px"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @php  foreach($discussions as $row):  @endphp
                                <tr>
                                    <td>{{  $row->subject }}
                                    </td>

                                    <td>{{  showDate('d/M/Y',$row->created_at) }}</td>
                                    <td>

                                        @php  if($row->admin==1): @endphp
                                        {{  __lang('administrators')  }},
                                        @php  endif;  @endphp

                                        @php  foreach($accountTable->getDiscussionAccounts($row->id) as $row2):  @endphp
                                        {{  $row2->name.' '.$row2->last_name }},
                                        @php  endforeach;  @endphp



                                    </td>

                                    <td>{{  boolToString($row->replied)  }}</td>

                                    <td class="text-right">
                                        <a href="{{  $this->url('application/viewdiscussion',array('id'=>$row->discussion_id)) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="View"><i class="fa fa-eye"></i> {{  __lang('view')  }}</a>

                                    </td>
                                </tr>
                                @php  endforeach;  @endphp

                                </tbody>
                            </table>


                        </div>

                    </div>

                </div>
                @php  endif;  @endphp

            </div>
        </div>
    </div>






@endsection
