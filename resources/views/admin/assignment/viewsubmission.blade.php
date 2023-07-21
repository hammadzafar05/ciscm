@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>$customCrumbs])
@endsection

@section('content')
<div class="card">
    <div class="card-header">{{ __lang('Student Information') }}</div>
    <div class="card-body">
        <table class="table table-striped">
            <tr>
                <td>{{ __lang('first-name') }}</td>
                <td>{{ $row->first_name }}</td>
            </tr>
            <tr>
                <td>{{ __lang('last-name') }}</td>
                <td>{{ $row->last_name }}</td>
            </tr>
            <tr>
                <td>{{ __lang('email') }}</td>
                <td>{{ $row->email }}</td>
            </tr>
            <tr>
                <td>{{ __lang('telephone-number') }}</td>
                <td>{{ $row->mobile_number }}</td>
            </tr>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header">{{ __lang('homework-details') }}</div>
    <div class="card-body">

        <dl class="dl-horizontal">
            <dt>{{ __lang('title') }}</dt>
            <dd>{{ $row->title }}</dd>
            <dt>{{ __lang('instruction') }}</dt>
            <dd><article class="readmore">
                    {!! $row->instruction !!}
                </article></dd>
            <dt>{{ __lang('due-date') }}</dt>
            <dd>{{ showDate('d/M/Y',$row->due_date) }}</dd>
            <dt>{{ __lang('assignment-type') }}</dt>
            <dd>
                @php
                switch($row->type){
                    case 't':
                        echo __lang('text');
                        break;
                    case 'f':
                        echo __lang('file-upload');
                        break;
                    case 'b':
                        echo __lang('text-file-upload');
                        break;
                }
                @endphp
            </dd>
            <dt>{{ __lang('passmark') }}</dt>
            <dd>{{ $row->passmark }}%</dd>
        </dl>




    </div>
</div>

<h3>{{ __lang('student-response') }}</h3>
@php if($row->type=='t' || $row->type=='b'): @endphp
    <div class="card">
        <div class="card-header">{{ __lang('answer') }}</div>
        <div class="card-body">
            {!! clean($row->content) !!}
        </div>
    </div>
@php endif;  @endphp

@php if($row->type=='f' || $row->type=='b'): @endphp
    <div class="card">
        <div class="card-header">{{ __lang('file') }}</div>
        <div class="card-body">
            <p><a href="{{ route('admin.assignment.download',['id'=>$row->id]) }}" >{{ basename($row->file_path) }}</a></p>

        </div>
    </div>
@php endif;  @endphp

@php if($row->type=='f' || $row->type=='b'): @endphp
    <div class="card">
        <div class="card-header">All File</div>
        <div class="card-body">
            <p><a href="{{ route('admin.assignment.download-pdf',['id'=>$row->id]) }}" >{{ basename($row->file_path_pdf) }}</a></p>

        </div>
    </div>
@php endif;  @endphp

<div class="card">
    <div class="card-header">{{ __lang('additional-comment') }}</div>
    <div class="card-body">
        <p>{{ $row->student_comment }}</p>
    </div>
</div>

<h3>{{ __lang('grade') }}</h3>

<div class="card">
    <div class="card-header">{{ __lang('grade-homework') }}</div>
    <div class="card-body">
        <form class="form" action="{{ url()->current() }}" method="post">
            @csrf
        <div class="form-group">
            {{ formLabel($form->get('admin_comment')) }}
            {{ formElement($form->get('admin_comment')) }}
        </div>

            <div class="form-group">
                {{ formLabel($form->get('grade')) }}
                {{ formElement($form->get('grade')) }}
            </div>


            <div class="form-group">
                {{ formLabel($form->get('editable')) }}
                {{ formElement($form->get('editable')) }}
            </div>


            <div class="form-group">
                <input type="checkbox" value="1" name="notify" checked/> {{ __lang('notify-student') }}
            </div>
<button class="btn btn-primary">{{ __lang('submit') }}</button>
        </form>
    </div>
</div>



@endsection

@section('footer')

    <script type="text/javascript" src="{{ asset('client/vendor/readmore/readmore.min.js') }}"></script>
    <script>
        $(function(){
            $('article.readmore').readmore({
                collapsedHeight : 200
            });
        });
    </script>
@endsection
