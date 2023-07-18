@extends('layouts.admin')

@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            url('/admin/external_certificates')=>__lang('external_certificates'),
            '#'=>__('default.view')
        ]])
@endsection
@section('pageTitle',__('default.edit').' '.__('default.external_certificate').': '.$external_certificate->title)
@section('innerTitle',__('default.edit').' '.__('default.external_certificate').': '.$external_certificate->title)

@section('content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >
                        <form method="POST" action="{{ url('admin/external_certificates' . '/' . $external_certificate->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                        @can('access','view_external_certificates')
                        <a href="{{ url('/admin/external_certificates') }}"  ><button  type="button" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('default.back')</button></a>
                        @endcan

                        @can('access','edit_external_certificate')
                        <a href="{{ url('/admin/external_certificates/' . $external_certificate->id . '/edit') }}"  ><button type="button" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i> @lang('default.edit')</button></a>
                        @endcan

                        @can('access','delete_external_certificate')

                            <button type="submit" class="btn btn-danger btn-sm" title="@lang('default.delete')" onclick="return confirm(&quot;@lang('default.confirm-delete')?&quot;)"><i class="fa fa-trash" aria-hidden="true"></i> @lang('default.delete')</button>

                        @endcan
                        </form>
                        <br/>
                        <br/>

                        <ul class="list-group">
                            <li class="list-group-item active">@lang('default.id')</li>
                            <li class="list-group-item">{{ $external_certificate->id }}</li>
                            <li class="list-group-item active">@lang('default.title')</li>
                            <li class="list-group-item">{{ $external_certificate->title }}</li>
                            <li class="list-group-item active">@lang('default.certificate_number')</li>
                            <li class="list-group-item">{!! $external_certificate->tracking_number !!}</li>
                            <li class="list-group-item active">@lang('default.program_name')</li>
                            <li class="list-group-item">{{ $external_certificate->course??"No Courses Added" }}</li>
                            <li class="list-group-item active">@lang('default.country')</li>
                            <li class="list-group-item">{{ $external_certificate->country??"No Country Added" }}</li>
                            <li class="list-group-item active">@lang('default.issue_date')</li>
                            <li class="list-group-item">{{ $external_certificate->issue_date }}</li>
                            <li class="list-group-item active">@lang('default.enabled')</li>
                            <li class="list-group-item">{{ $external_certificate->status == 1 ? 'Enabled' : 'Disabled' }}</li>

                        </ul>



                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
