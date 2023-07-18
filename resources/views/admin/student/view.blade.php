@extends(adminLayout())

@section('content')
<div class="container-fluid">

    <div class="row" style="margin-bottom:  10px;">

        <div class="col-md-2 text-highlight-support5 "><strong>{{ __lang('first-name') }}:</strong></div>
        <div class="col-md-4">{{ htmlentities( $row->name) }}</div>



        <div class="col-md-2 text-highlight-support5 "><strong>{{ __lang('last-name') }}</strong></div>
        <div class="col-md-4">{{ htmlentities( $row->last_name) }}</div>

    </div>

       <div class="row" style="margin-bottom:  10px;">

           <div class="col-md-2 text-highlight-support5 "><strong>{{ __lang('telephone-number') }}</strong></div>
           <div class="col-md-4">{{ htmlentities( $row->mobile_number) }}</div>



        <div class="col-md-2 text-highlight-support5 "><strong>{{ __lang('email') }}</strong></div>
        <div class="col-md-4">{{ htmlentities( $row->email) }}</div>

    </div>

      <div class="row" style="margin-bottom:  10px;">

        <div class="col-md-2 text-highlight-support5 "><strong>{{ __lang('status') }}</strong></div>
        <div class="col-md-4">{{ htmlentities( (empty($row->enabled))? 'Inactive':'Active') }}</div>

          <div class="col-md-2 text-highlight-support5 "><strong>{{ __lang('display-picture') }}</strong></div>
          <div class="col-md-4">


              @php if(!empty($row->picture) && isUrl($row->picture)): @endphp
                  <img src="{{$row->picture}}" style="max-width: 200px" alt=""/>
              @php elseif(!empty($row->picture) && isImage($row->picture)): @endphp
                  <img src="{{ resizeImage($row->picture,200,200,basePath()) }}" alt=""/>

              @php endif;  @endphp
          </div>




    </div>





        @php foreach($custom as $row):  @endphp
           <div class="row">
        @php if($row->type=='checkbox'): @endphp
        <div  style="margin-bottom:  10px;" class="col-md-4 text-highlight-support5 "><strong>{{ htmlentities( $row->name) }}</strong></div>
        <div  style="margin-bottom:  10px;" class="col-md-8">{{ htmlentities( boolToString($row->value)) }}</div>
       @php elseif($row->type=='file'):  @endphp
                <div  style="margin-bottom:  10px;" class="col-md-4 text-highlight-support5 "><strong>{{ htmlentities( $row->name) }}</strong></div>
                <div  style="margin-bottom:  10px;" class="col-md-8">
                    @php if(isImage($row->value)): @endphp

                        <img src="{{ resizeImage($row->value, 200, 200, basePath()) }}" alt=""/> <br/>
                    @php endif;  @endphp
                    <a target="_blank" href="{{ basePath().'/'.$row->value }}">{{ __lang('view-file') }}</a>

                </div>
        @php else:  @endphp
                <div  style="margin-bottom:  10px;" class="col-md-4 text-highlight-support5 "><strong>{{ htmlentities( $row->name) }}</strong></div>
                <div  style="margin-bottom:  10px;" class="col-md-8">{{ htmlentities( $row->value) }}</div>
        @php endif;  @endphp
           </div>
         @php endforeach;  @endphp








    </div>

        <div>
            <h4>{{ __lang('enrolled-in') }}</h4>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>{{ __lang('course-session') }}</th>
                    <th>{{ __lang('completed-classes') }}</th>
                    <th>{{ __lang('enrolled-on') }}</th>
                </tr>
                </thead>
                <tbody>
                <tbody>
                @php foreach(\App\Student::find($id)->studentCourses()->whereHas('course')->get() as $session): @endphp
                    <tr>
                        <td>{{$session->course->name}}</td>
                        <td>@php $attended= $attendanceTable->getTotalDistinctForStudentInSession($id,$session->course_id); echo $attended @endphp/{{ \App\Course::find($session->course_id)->lessons()->count() }}</td>
                        <td>{{showDate('d/M/Y',$session->created_at)}}</td>
                    </tr>

                @php endforeach; @endphp
                </tbody>


                </tbody>
            </table>
        </div>

    @php if(false): @endphp
    <div>
        <h2>{{ __lang('classes-attended') }}</h2>
        <table class="table table-stripped">
            <thead>
            <tr>
                <th>{{ __lang('class') }}</th>
                <th>{{ __lang('session-course') }}</th>
                <th>{{ __lang('date') }}</th>
                <th>{{ __lang('actions') }}</th>
            </tr>
            </thead>
            @php foreach($attendance as $row):  @endphp
            <tr>
                <td>{{ htmlentities( $row->lesson_name) }}</td>
                <td>{{ htmlentities( $row->session_name) }}</td>
                <td>{{ htmlentities( showDate('d/M/Y',$row->attendance_date)) }}</td>
                <td><button title="Delete" onclick="openPopup('{{ adminUrl(array('controller'=>'student','action'=>'deleteattendance','id'=>$row->attendance_id)) }}')" href=""  class="btn btn-xs btn-primary btn-equal" data-toggle="tooltip" data-placement="top" data-original-title="{{__lang('delete')}}"><i class="fa fa-trash"></i></button></td>
            </tr>
            @php endforeach;  @endphp
        </table>
    </div>
    @php endif;  @endphp




</div>
@endsection
