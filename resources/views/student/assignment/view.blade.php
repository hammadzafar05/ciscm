@extends(studentLayout())

@section('content')
@php  if($row->type=='t' || $row->type=='b'): @endphp
    <div class="card card-primary">
        <div class="card-header">{{  __lang('answer')  }}</div>
        <div class="card-body">
            {!! clean($row->content ) !!}
        </div>
    </div>
@php  endif;  @endphp

@php  if($row->type=='f' || $row->type=='b'): @endphp
     <div class="card card-primary">
        <div class="card-header">{{  __lang('file')  }}</div>
        <div class="card-body">
            <p><a href="{{  url('/')  }}/{{  $row->file_path  }}" target="_blank">{{  basename($row->file_path)  }}</a></p>

        </div>
    </div>
@php  endif;  @endphp

<div class="card card-primary">
    <div class="card-header">{{  __lang('additional-comment')  }}</div>
    <div class="card-body">
        <p>{{  $row->student_comment  }}</p>
    </div>
</div>
@endsection
