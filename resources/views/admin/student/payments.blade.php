@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('content')

<div class="card">

    <div class="box-body no-padding">
        <form action="@php $this->url('admin/payments');  @endphp" method="get">
        <div class="row" style="margin-bottom: 20px">
            <div class="col-md-2">
                <input value="{{ $startDate }}" type="text" class="form-control date" name="start" placeholder="{{ __lang('start-date') }}"/>
            </div>
            <div class="col-md-2">
                <input value="{{ $endDate }}" type="text" class="form-control date" name="end" placeholder="{{ __lang('end-date') }}"/>
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary" type="submit">{{ __lang('filter') }}</button>
            </div>
            <div class="col-md-4">
               <strong style="font-size: 25px">{{ __lang('total') }}: {{ $this->formatPrice($sum) }}</strong>
            </div>
        </div>
        </form>

        <div class="table-responsive">
        <table class="table table-hover table-striped no-margin">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ __lang('student') }}</th>
                <th>{{ __lang('payment-method') }}</th>
                <th>{{ __lang('amount') }}</th>
                <th>{{ __lang('added-on') }}</th>
            </tr>
            </thead>
            <tbody>



            @php foreach($paginator as $row):  @endphp
                <tr>
                    <td>{{ $row->payment_id }}</td>
                    <td>{{ $row->name }} {{ $row->last_name }} ({{ $row->email }})</td>
                    <td>{{ $row->payment_method }}</td>
                    <td>{{ $this->formatPrice($row->amount) }}</td>
                    <td>{{ showDate('d/M/Y',$row->added_on) }}</td>

                </tr>
            @php endforeach;  @endphp





            </tbody>
        </table>
    </div>

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
                'route' => 'admin/payments',
                'start'=> $startDate,
                'end' => $endDate
            )
        );
        @endphp

    </div><!--end .box-body -->
</div><!--end .box -->


{{ $this->headLink()->prependStylesheet(basePath().'/pickadate/themes/default.date.css')
                    ->prependStylesheet(basePath().'/pickadate/themes/default.css') }}
<script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/picker.js"></script>
<script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/picker.date.js"></script>
<script type="text/javascript" src="{{ basePath() }}/client/vendor/pickadate/legacy.js"></script>
<script type="text/javascript">

        jQuery(function(){
            jQuery('.date').pickadate({
                format: 'yyyy-mm-dd'
            });
        });

</script>
@endsection
