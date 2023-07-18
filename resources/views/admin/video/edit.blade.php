@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            route('admin.video.index')=>__lang('video-library'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('content')
    <div  >
        <div >
            <div class="box">

                <div class="box-body">


                    <form enctype="multipart/form-data" method="post" action="{{ adminUrl(array('controller'=>'video','action'=>'edit','id'=>$id)) }}">
                    @csrf
                    <div class="form-group">
                        {{formLabel($form->get('name'))}}
                        {{formElement($form->get('name'))}}

                            <p class="help-block"> {{formElementErrors($form->get('name'))}} </p>

                    </div>






                    <div class="form-group">
                        {{formLabel($form->get('description'))}}


                        {{formElement($form->get('description'))}}

                        <p class="help-block"> {{formElementErrors($form->get('description'))}} </p>

                    </div>
                    <div class="form-group">
                        {{formLabel($form->get('length'))}}


                        {{formElement($form->get('length'))}}

                            <p class="help-block"> {{formElementErrors($form->get('length'))}} </p>

                    </div>




                    <div class="form-group">
                        {{formLabel($form->get('picture'))}}

                        <div class="row_">
                            <div class="col-md-6_">
                                {{formElement($form->get('picture'))}}

                                  <p class="help-block"> {{formElementErrors($form->get('picture'))}} </p>
                            </div>
                            <div class="col-md-6_">
                                  @if(!empty($picture) && isImage($picture))
                                <img style="max-width: 200px;max-height: 200px" src="{{ basePath() }}/{{ $picture }}?rand={{ time() }}" alt=""/>
                                <a class="btn btn-danger btn-sm" onclick="return confirm('{{ __lang('confirm-remove-picture') }}')" href="{{ adminUrl(['controller'=>'video','action'=>'removeimage','id'=>$id]) }}"><i class="fa fa-trash"></i> {{  __lang('Remove image')  }}</a>


                                  @endif
                            </div>
                        </div>

                    </div>





                    <div class="form-footer">
                        <br><br>
                        <button type="submit" class="btn btn-primary">{{  __lang('Save Changes')  }}</button>
                    </div>
                    </form>
                </div>
            </div><!--end .box -->
        </div><!--end .col-lg-12 -->
    </div>



@endsection

@section('footer')
    <script type="text/javascript" src="{{ asset('client/vendor/ckeditor/ckeditor.js') }}"></script>
    <script type="text/javascript">

        CKEDITOR.replace('description', {
            filebrowserBrowseUrl: '  $this->basepath()  /admin/filemanager',
            filebrowserImageBrowseUrl: '  $this->basepath()  /admin/filemanager',
            filebrowserFlashBrowseUrl: '  $this->basepath()  /admin/filemanager'
        });



    </script>
@endsection
