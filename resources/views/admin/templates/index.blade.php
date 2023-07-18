@extends('layouts.admin')


@section('pageTitle',__('default.site-theme'))
@section('innerTitle',__('default.site-theme'))
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>__('default.site-theme')
        ]])
@endsection
@section('content')

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    @lang('default.active-template')
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div style="width: 100%; height: 121px; overflow: hidden" class="rounded">
                                <a href="#"   data-toggle="modal" data-target="#{{ $currentTemplate->directory }}Modal" > <img src="{{ asset('templates/'.$currentTemplate->directory.'/preview.jpg') }}"  class="img-fluid rounded mx-auto d-block" /></a>
                            </div>
                            </div>
                        <div class="col-md-6">
                            <h3>{{ $currentTemplate->name }}</h3>
                            <p>
                                @lang(tlang($currentTemplate->directory,'app-description'))
                            </p>
                            <!-- Default dropup button -->
                            <div class="btn-group dropup">
                                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-cogs"></i> @lang('default.customize')
                                </button>
                                <div class="dropdown-menu wide-btn">
                                    <a class="dropdown-item" href="{{ route('admin.templates.settings') }}">@lang('default.settings')</a>
                                    <a class="dropdown-item" href="{{ route('admin.templates.colors') }}">@lang('default.colors')</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <div class="card">
        <div class="card-header">@lang('default.all-templates')</div>
        <div class="card-body">
            <div class="row">

                @foreach($templates as $template)
                    <div class="col-md-6 int_mb60 mb-5"  >
                        <div class="row">
                            <div class="col-md-6">
                                <div style="width: 100%; height: 121px; overflow: hidden" class="rounded">
                                <a href="#"  data-toggle="modal" data-target="#{{ $template }}Modal" ><img src="{{ asset('templates/'.$template.'/preview.jpg') }}"  class="img-fluid rounded mx-auto d-block" /></a>
                                </div>
                                @section('footer')
                                    @parent
                                <!-- Modal -->
                                <div class="modal fade" id="{{ $template }}Modal" tabindex="-1" role="dialog" aria-labelledby="{{ $template }}ModalLabel" aria-hidden="true">
                                    <div class="modal-dialog  modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="{{ $template }}ModalLabel">{{ templateInfo($template)['name'] }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <img src="{{ asset('templates/'.$template.'/preview.jpg') }}"  class="img-fluid" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    @endsection


                            </div>
                            <div class="col-md-6">
                                <h3>{{ templateInfo($template)['name'] }}</h3>
                                <p>
                                    @lang(tlang($template,'app-description'))
                                </p>
                                <!-- Default dropup button -->
                                @if($currentTemplate->directory ==$template)
                                <div class="btn-group dropup">
                                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-cogs"></i> @lang('default.customize')
                                    </button>
                                    <div class="dropdown-menu wide-btn">
                                        <a class="dropdown-item" href="{{ route('admin.templates.settings') }}">@lang('default.settings')</a>
                                        <a class="dropdown-item" href="{{ route('admin.templates.colors') }}">@lang('default.colors')</a>
                                    </div>
                                </div>
                                    @else
                                    <a class="btn btn-primary" href="{{ route('admin.templates.install',['templateDir'=>$template]) }}"><i class="fa fa-download"></i> @lang('default.install')</a>
                                @endif

                            </div>
                        </div>

                    </div>


                    @endforeach

            </div>


        </div>
    </div>


@endsection
