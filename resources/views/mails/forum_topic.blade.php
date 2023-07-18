@extends('mails.layout')

@section('content')
{{ __lang('new-topics-mail',['count'=>count($topics)]) }}<br/>
<table style="width:100%" class="table-layout">
    <thead>
        <tr>
            <th style="text-align: left;">{{__('Topic')}}</th>
            <th style="text-align: left;">{{__('Created By')}}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($topics as $topic)
        <tr>
            <td><a style="text-decoration: underline" href="{{ route($module.'.forum.topic',['id'=>$topic->id]) }}">{{ $topic->title }}</a></td>
            <td>{{ $topic->user->name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
