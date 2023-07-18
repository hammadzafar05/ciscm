@extends(adminLayout())
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('content')
<div>
    <div >

        <div class="card">
            <form action="{{ adminUrl(['controller'=>'download','action'=>'addsession','id'=>$id]) }}" method="post">
            @csrf
            <div class="card-body">

                <div >

                       <input class="btn btn-primary" type="submit" value="{{ __lang('add-session-course') }}"/>
                    <br><br>
                    <div>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th></th>
                            <th>{{ __lang('id') }}</th>
                            <th>{{ __lang('session-course') }}</th>
                            <th>{{ __lang('start-date') }}</th>
                            <th>{{ __lang('end-date') }}</th>
                            <th>{{ __lang('total-attended') }}</th>
                            <th>{{ __lang('total-enrolled') }}</th>
                            <th>{{ __lang('status') }}</th>

                        </tr>
                        </thead>
                        <tbody>
                        @php foreach($paginator as $row):  @endphp
                            <tr>
                                <td>

                                    <input name="session_{{ $row->id }}" value="{{ $row->id }}" type="checkbox"/>
                                </td>
                                <td><span class="label label-success">{{ $row->id }}</span></td>
                                <td>{{ $row->name }}</td>
                                <td>{{ showDate('d/m/Y',$row->start_date) }}</td>
                                <td>{{ showDate('d/m/Y',$row->end_date) }}</td>
                                <td>
                                    <strong>{{ $attendanceTable->getTotalStudentsForSession($row->id) }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $studentSessionTable->getTotalForSession($row->id) }}</strong>
                                </td>
                                <td>
                                    {{ ($row->enabled!=1)?__lang('disabled'):__lang('enabled') }}
                                </td>


                            </tr>
                        @php endforeach;  @endphp

                        </tbody>
                    </table>
                    @php
                    // add at the end of the file after the table
                    echo paginationControl(
                    // the paginator object
                        $paginator,
                        // the scrolling style
                        'sliding',
                        // the partial to use to render the control
                        null,
                        // the route to link to when a user clicks a control link
                        array(
                            'route' => 'admin/default',
                            'controller'=>'download',
                            'action'=>'browsesessions',
                            'id'=>$id
                        )
                    );
                    @endphp
                </div>
                    <h3>{{ __lang('assigned-sessions') }}</h3>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th></th>
                            <th>{{ __lang('id') }}</th>
                            <th>{{ __lang('session-course-name') }}</th>
                            <th>{{ __lang('start-date') }}</th>
                            <th>{{ __lang('end-date') }}</th>
                            <th>{{ __lang('total-attended') }}</th>
                            <th>{{ __lang('total-enrolled') }}</th>
                            <th>{{ __lang('status') }}</th>

                        </tr>
                        </thead>
                        <tbody>
                        @php foreach($assigned as $row):  @endphp
                            <tr>
                                <td>

                                    <input name="session_{{ $row->id }}" value="{{ $row->id }}" type="checkbox"/>
                                </td>
                                <td><span class="label label-success">{{ $row->id }}</span></td>
                                <td>{{ $row->name }}</td>
                                <td>{{ showDate('d/m/Y',$row->start_date) }}</td>
                                <td>{{ showDate('d/m/Y',$row->end_date) }}</td>
                                <td>
                                    <strong>{{ $attendanceTable->getTotalStudentsForSession($row->id) }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $studentSessionTable->getTotalForSession($row->id) }}</strong>
                                </td>
                                <td>
                                    {{ ($row->enabled!=1)?__lang('disabled'):__lang('enabled') }}
                                </td>


                            </tr>
                        @php endforeach;  @endphp

                        </tbody>
                    </table>
                </div>

            </div><!--end .box-body -->
            </form>

        </div><!--end .box -->
    </div><!--end .col-lg-12 -->
</div>

@endsection
