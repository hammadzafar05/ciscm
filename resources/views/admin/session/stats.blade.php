@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>$customCrumbs])
@endsection

@section('content')
<div class="mb-3">
    <div style="text-align: center"><h2>{{ $percentage }}%</h2></div>
    <div class="progress">
        <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="{{ $percentage }}" style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}"></div>
    </div>
</div>


<div class="card">
    <div class="card-header">
        <h2>{{ $row->course_name }}</h2>
    </div>
    <div class="card-body">


        <div class="" role="tabpanel" data-example-id="togglable-tabs">
            <ul id="myTab" class="nav nav-pills" role="tablist">
                <li class="nav-item"><a  class="nav-link active" href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">{{ __lang('classes-attended') }}</a>
                </li>
                <li class="nav-item"><a class="nav-link"  href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">{{ __lang('test-results') }}</a>
                </li>

            </ul>
            <div id="myTabContent" class="tab-content">
                <div role="tabpanel" class="tab-pane   active in" id="tab_content1" aria-labelledby="home-tab">
                    <table class="table table-stripped">
                        <thead>
                        <tr>
                            <th>{{ __lang('class') }}</th>
                            <th>{{ __lang('date') }}</th>
                            <th>{{ __lang('action') }}</th>
                        </tr>
                        </thead>
                        @php foreach($attended as $row):  @endphp
                            <tr>
                                <td>{{ htmlentities( $row->name) }}</td>
                                <td>{{ htmlentities( showDate('d/M/Y',$row->attendance_date)) }}</td>
                                <td><button title="Delete" onclick="openPopup('{{ adminUrl(array('controller'=>'student','action'=>'deleteattendance','id'=>$row->id)) }}')" href=""  class="btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{__lang('delete')}}"><i class="fa fa-trash"></i></button></td>
                            </tr>
                        @php endforeach;  @endphp
                    </table>
                </div>
                <div role="tabpanel" class="tab-pane  " id="tab_content2" aria-labelledby="profile-tab">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>{{ __lang('test') }}</th>
                            <th>{{ __lang('result') }}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            @php foreach($testResults as $value):  @endphp
                                <tr>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->score }}% (@if($value->score >= $value->passmark)
                                            <span style="color: green">{{ __lang('Passed') }}</span>
                                        @else
                                            <span style="color: red">{{ __lang('Failed') }}</span>
                                        @endif)</td>
                                    <td> <a onclick="openModal('{{ $value->name }} {{ $value->last_name }}','{{ adminUrl(array('controller'=>'test','action'=>'testresult','id'=>$value->id)) }}')"  href="javascript:;" class="btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{ __lang('view-result') }}"><i class="fa fa-eye"></i></a></td>
                                </tr>
                            @php endforeach;  @endphp
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
</div>


@endsection
