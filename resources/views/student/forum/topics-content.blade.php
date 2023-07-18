@if($lecture)
    <div>
        <p class="lead">
            {{ __lang('Lecture') }}: {{$lecture->title}}
        </p>
    </div>
@endif
<!--breadcrumb-section ends-->
<!--container starts-->
<div class="card">
    <!--primary starts-->

    <div class="card-body">
        <div class="mb-2">
            <a target="{{ @$target }}" class="btn btn-primary" href="{{ route('student.forum.addtopic',['id'=>$id])}}"><i class="fa fa-plus"></i> {{ __lang('Add Topic') }}</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th>{{ __lang('Topic') }}</th>
                    <th>{{ __lang('Created By') }}</th>
                    <th>{{ __lang('Added On') }}</th>
                    <th >{{ __lang('Replies') }}</th>
                    <th>{{ __lang('Last Reply') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @php  foreach($topics as $row):  @endphp

                <tr>
                    <td>{{  $row->title }}</td>
                    <td>
                        {{  $row->user->name  }}
                    </td>
                    <td>{{ showDate('d/M/Y',$row->created_at) }}</td>
                    <td>{{ ($row->forumPosts->count()-1)  }}</td>
                    <td>@php  if($row->forumPosts->count()-1 > 0): @endphp
                        {{ showDate('D, d M Y g:i a',$row->forumPosts()->orderBy('id','desc')->first()->created_at) }}
                        @php  endif;  @endphp
                    </td>
                    <td class="text-right">
                        <a  target="{{ @$target }}"  class="btn btn-primary" href="{{ route('student.forum.topic',['id'=>$row->id]) }}">{{ __lang('View') }}</a>

                        @if(\Illuminate\Support\Facades\Auth::user()->id==$row->user_id)
                            <a onclick="return confirm('Are you sure you want to delete this topic and all its posts?')" class="btn btn-danger" href="{{ route('student.forum.deletetopic',['id'=>$row->id]) }}">{{ __lang('Delete Topic') }}</a>
                        @endif
                    </td>
                </tr>

                @php  endforeach;  @endphp

                </tbody>
            </table>
        </div>
        @php
            // add at the end of the file after the table
            echo $topics->links();
        @endphp
    </div>


</div>

<!--container ends-->
