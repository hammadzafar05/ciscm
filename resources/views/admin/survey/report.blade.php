@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.survey.index')=>__lang('surveys'),
            '#'=>__lang('reports')
        ]])
@endsection

@section('content')

@php foreach($survey->surveyQuestions as $question):  @endphp
    <div class="card">
        <div class="card-header">{!! $question->question !!}</div>
        <div class="card-body">
            <table class="table">
    <thead>
    <tr>
        <th>{{ __lang('option') }}</th>
        <th>{{ __lang('percentage') }}</th>
    </tr>
    </thead>
                @php foreach($question->surveyOptions as $option):  @endphp
                <tr>
                    @php $percent = $controller->getOptionPercent($option->id)  @endphp
                    <td>{{ $option->option }}</td>
                    <td>{{ $percent }}%
                        <br/>
                        <div class="progress progress_sm" style="width: 76%;">
                            <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="{{ $percent }}" style="width: {{ $percent }}%;" aria-valuenow="{{ $percent }}"></div>
                        </div>
                    </td>
                </tr>
                @php endforeach;  @endphp

            </table>
        </div>
    </div>


@php endforeach;  @endphp
@endsection
