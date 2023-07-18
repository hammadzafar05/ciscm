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

      <ul class="nav nav-pills" id="myTab3" role="tablist">
                            <li class="nav-item">
                              <a class="nav-link active" id="home-tab3" data-toggle="tab" href="#home3" role="tab" aria-controls="home" aria-selected="true">{{ __lang('import') }}</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="profile-tab3" data-toggle="tab" href="#profile3" role="tab" aria-controls="profile" aria-selected="false">{{ __lang('export') }}</a>
                            </li>
                          </ul>
                          <div class="tab-content" id="myTabContent2">
                            <div class="tab-pane fade show active" id="home3" role="tabpanel" aria-labelledby="home-tab3">

                                    <div class="card">
                                     <div class="card-header">
                                         {{ __lang('import-new-students') }}
                                    </div>
                                    <div class="card-body">
                                        <form onsubmit="return confirm('{{__lang('import-students-confirm')}}')" enctype="multipart/form-data" class="form" method="post" action="{{ adminUrl(array('controller'=>'student','action'=>'import')) }}">
                                            @csrf
                                            <p>
                                                {!!  clean(__lang('import-students-help',['link'=> adminUrl(array('controller'=>'student','action'=>'csvsample'))])) !!}
                                            </p>

                                            <div class="form-group" style="padding-bottom: 10px">
                                                <label for="file">{{ __lang('csv-file') }}</label>
                                                <input required="required" name="file" type="file"/>
                                            </div>

                                            <button class="btn btn-primary" type="submit">{{ __lang('import') }}</button>
                                        </form>
                                    </div>
                                    </div>



                            </div>
                            <div class="tab-pane fade" id="profile3" role="tabpanel" aria-labelledby="profile-tab3">

                                <div class="card">
                                 <div class="card-header">
                                     {{ __lang('export-all-students') }}
                                </div>
                                <div class="card-body">
                                    <p>
                                        <a class="btn btn-primary " href="{{ adminUrl(['controller'=>'student','action'=>'exportstudents']) }}">{{ __lang('export') }}</a>
                                    </p>
                                </div>
                                </div>


                            </div>
                          </div>





@endsection
