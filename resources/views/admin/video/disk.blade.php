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
<div class="card-body">
    <div class="row">



        <div class="col-md-12">
            <table class="table table-striped">

                <tr>
                    <th>{{ __lang('total-videos') }}:</th>
                    <td>{{ $total }}</td>
                </tr>
                <tr>
                    <th>{{ __lang('disk-usage') }}:</th>
                    <td>{{ $diskUsage }}</td>
                </tr>
            </table>
        </div>

    </div>
</div>
</div>

@endsection
