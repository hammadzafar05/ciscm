@extends('layouts.admin')

@section('pageTitle',__('default.menus'))
@section('innerTitle',__('default.header-menu'))
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>__('default.header-menu')
        ]])
@endsection


@section('content')

    <div class="row">

        <div class="col-md-4">
            <h5>@lang('default.add-links')</h5>
            <div  id="accordionExample">
                <div class="accordion">
                    <div class="accordion-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <h4>
                               @lang('default.pages')

                        </h4>
                    </div>

                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="accordion-body">
                            <ul class="list-group">
                                @foreach($pages as $page)

                                <li class="list-group-item @if($page['name'] == 'Terms & Conditions' || $page['name'] == 'Privacy Policy') display-hide @endif">
                                    {{ $page['name'] }}
                                    <form method="post" class="menuform int_inlinedisp" action="{{ route('admin.menus.save-header') }}">
                                        @csrf
                                        <input type="hidden" name="name" value="{{ $page['name'] }}"/>
                                        <input type="hidden" name="label" value="{{ $page['name'] }}"/>
                                        <input type="hidden" name="url" value="{{ $page['url'] }}"/>
                                        <input type="hidden" name="type" value="p"/>
                                    <span onclick="$(this).parent().submit()"  class="int_curpoin badge badge-primary badge-pill float-right">@lang('default.add')</span>
                                    </form>
                                </li>

                                @endforeach
                            </ul>

                        </div>
                    </div>
                </div>
                <div class="accordion">
                    <div class="accordion-header" id="headingTwo" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <h4>
                                @lang('default.articles')

                        </h4>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="accordion-body">
                            <ul class="list-group">
                                @foreach($articles as $page)

                                    <li class="list-group-item">{{ $page['name'] }}
                                        <form method="post" class="menuform int_inlinedisp" action="{{ route('admin.menus.save-header') }}">
                                            @csrf
                                            <input type="hidden" name="name" value="@lang('default.article'): {{ $page['name'] }}"/>
                                            <input type="hidden" name="label" value="{{ $page['name'] }}"/>
                                            <input type="hidden" name="url" value="{{ $page['url'] }}"/>
                                            <input type="hidden" name="type" value="a"/>
                                            <span onclick="$(this).parent().submit()"  class="int_curpoin badge badge-primary badge-pill float-right">@lang('default.add')</span>
                                        </form>
                                    </li>

                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="accordion">
                    <div class="accordion-header" id="headingFour">
                        <h4   data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                               @lang('default.categories')
                        </h4>
                    </div>
                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                        <div class="accordion-body">
                            <ul class="list-group">
                                @foreach($categories as $page)

                                    <li class="list-group-item">{{ $page['name'] }}
                                        <form method="post" class="menuform int_inlinedisp" action="{{ route('admin.menus.save-header') }}">
                                            @csrf
                                            <input type="hidden" name="name" value="@lang('default.category'): {{ $page['name'] }}"/>
                                            <input type="hidden" name="label" value="{{ $page['name'] }}"/>
                                            <input type="hidden" name="url" value="{{ $page['url'] }}"/>
                                            <input type="hidden" name="type" value="g"/>
                                            <span onclick="$(this).parent().submit()"  class="int_curpoin badge badge-primary badge-pill float-right">@lang('default.add')</span>
                                        </form>
                                    </li>

                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="accordion">
                    <div class="accordion-header" id="headingThree" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        <h4>
                                @lang('default.custom')
                        </h4>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                        <div class="accordion-body">

                            <form method="post" id="customForm" class="menuform resetform" action="{{ route('admin.menus.save-header') }}">
                                @csrf
                                <input type="hidden" name="name" value="@lang('default.custom')" />
                                <input type="hidden" name="type" value="c"/>
                                <div class="form-group">
                                    <label for="label">@lang('default.label')</label>
                                    <input required class="form-control" type="text" name="label" value=""/>
                                </div>

                                    <div class="form-group">
                                        <label for="url">URL</label>
                                        <input required  class="form-control" type="text" name="url" value=""/>
                                    </div>


                                <div class="form-group">
                                    <label for="sort_order">@lang('default.sort-order')</label>
                                    <input class="form-control number" type="text" name="sort_order" value=""/>
                                </div>

                                <div class="form-group">
                                    <label for="parent_id">@lang('default.parent')</label>
                                    <select class="form-control" name="parent_id" id="parent_id">
                                        <option value="0">@lang('default.none')</option>
                                        @foreach(\App\HeaderMenu::where('parent_id',0)->orderBy('sort_order')->get() as $option)
                                            <option value="{{ $option->id }}">{{ $option->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" name="new_window" type="checkbox" value="1" id="new_windowc">
                                    <label class="form-check-label" for="new_windowc">
                                        @lang('default.new-window')
                                    </label>
                                </div>
                                <br/>


                                <button class="btn btn-primary float-right">@lang('default.add')</button>

                            </form>


                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-8" >
        <h4>@lang('default.menu')</h4>
            <div>
                <img src="{{ asset('img/loader.gif') }}" id="loaderImg" class="int_hide"/>
            </div>
            <div id="menubox" >

            </div>

        </div>


    </div>

@endsection


@section('header')
    <link rel="stylesheet" href="{{ asset('client/vendor/jquery-toast-plugin/dist/jquery.toast.min.css') }}">
@endsection

@section('footer')
    <script src="{{ asset('client/vendor/jquery-toast-plugin/dist/jquery.toast.min.js') }}"></script>
    <script src="{{ asset('client/vendor/jquery/jquery.form.min.js') }}" type="text/javascript"></script>
<script>
"use strict";

    function loadMenu(){
        $('#loaderImg').show();
        $('#menubox').load('{{ route('admin.menus.load-header') }}',function(){
            $('#loaderImg').hide();
        });

    }

    loadMenu();

    $(document).on('submit','.menuform',function(e){
        e.preventDefault();
        $.toast('@lang('default.saving')');

        var formId = $(this).attr('id');
        $(this).ajaxSubmit({
            success: function(responseText, statusText, xhr, $form){
                if(responseText.status){
                    $.toast('@lang('default.changes-saved')');
                    loadMenu();

                    if(formId=='customForm'){
                        document. getElementById("customForm").reset();
                    }
                }
                else{
                    $.toast(responseText.error);
                }
            },
            error: function(jqXHR,textStatus,errorThrown){
                $.toast('@lang('default.error-occurred')');
            }
        });
    });

    $(document).on('click','.deletemenu',function(e){
        e.preventDefault();
        $.toast('@lang('default.removing')');
        $.get($(this).attr('href'),function(data){
            loadMenu();
        });
    });




</script>

@endsection
