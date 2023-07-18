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
<div class="" role="tabpanel" data-example-id="togglable-tabs">
    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
        <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">{{ __lang('settings') }}</a>
        </li>
        <li id="methodtab" role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">{{ __lang('currencies') }}</a>
        </li>
    </ul>
    <div id="myTabContent" class="tab-content">
        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
            @php 
            $form->prepare();
            $form->setAttribute('action', adminUrl(array('controller'=>'payment','action'=>'edit','id'=>$id)));
            $form->setAttribute('method', 'post');
            $form->setAttribute('role', 'form');
            $form->setAttribute('class', 'form-horizontal');
            $form->get('is_global')->setAttribute('id','is_global');
            echo $this->form()->openTag($form);
            @endphp
            <div class="row">
                <div >
                    <div class="card">

                        <div class="card-body">
                            <div class="form-group">
                                <div class="col-sm-2">
                                    <label for="status">{{ __lang('label') }}</label>
                                </div>
                                <div class="col-sm-10">
                                    {{ formElement($form->get('method_label')) }}
                                </div>
                            </div>
                            @php foreach($fields as $row): @endphp

                                <div class="form-group">
                                    <div class="col-sm-2">
                                        {{ formLabel($form->get($row->key));   }}
                                    </div>
                                    <div class="col-sm-10">
                                        @php if($row->type == 'radio'): @endphp
                                            {{ $this->formRadio($form->get($row->key));    }}
                                        @php else: @endphp
                                            {{ formElement($form->get($row->key));    }}
                                        @php endif;  @endphp
                                    </div>
                                </div>

                            @php endforeach;  @endphp
                            <div class="form-group">
                                <div class="col-sm-2">
                                    <label for="status">{{formLabel($form->get('is_global'))}}</label>
                                </div>
                                <div class="col-sm-10">
                                    {{ formElement($form->get('is_global')) }}
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-2">
                                    <label for="status">{{ __lang('status') }}</label>
                                </div>
                                <div class="col-sm-10">
                                    {{ formElement($form->get('status')) }}
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-2">
                                    <label for="status">{{ __lang('sort-order') }}</label>
                                </div>
                                <div class="col-sm-10">
                                    {{ formElement($form->get('sort_order')) }}
                                </div>
                            </div>




                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div >
                    <button class="btn btn-primary" type="submit">{{__lang('save-changes')}}</button>
                </div><!--end .col-lg-12 -->
            </div>

             </form>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
            <div id="currencylist">



            </div>

        </div>
    </div>
</div>


<script type="text/javascript">
    $(function(){
        $('#currencylist').load('{{adminUrl(['controller'=>'payment','action'=>'currencies','id'=>$id])}}',function(){
            $('.select2').select2();
        });
        $(document).on('submit','#currencyform',function(event){
            var $this = $(this);
            var frmValues = $this.serialize();
            $('#currencylist').html(' <img  src="{{ basePath() }}/img/ajax-loader.gif">');

            $.ajax({
                type: $this.attr('method'),
                url: $this.attr('action'),
                data: frmValues
            })
                .done(function (data) {
                    $('#currencylist').html(data);
                })
                .fail(function () {
                    $('#currencylist').text("{{__lang('error-occurred')}}");
                });
            event.preventDefault();

        });

        $(document).on('click','#currencylist a.delete',function(e){
            e.preventDefault();
            $('#currencylist').html(' <img  src="{{ basePath() }}/img/ajax-loader.gif">');

            $('#currencylist').load($(this).attr('href'));
        });
    });

    toggleTab();
    $('#is_global').click(function(){
        toggleTab();
    });

    function toggleTab() {
        console.log('checked');
        if ($('#is_global').prop("checked")) {

            $('#methodtab').hide();

        }
        else {


            $('#methodtab').show();

        }
    }
</script>

@endsection
