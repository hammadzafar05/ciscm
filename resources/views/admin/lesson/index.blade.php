@extends('layouts.admin')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('search-form')
    <form class="form-inline mr-auto" method="get" action="{{ adminUrl(array('controller'=>'lesson','action'=>'index')) }}">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
        </ul>
        <div class="search-element">
            <input value="{{ request()->get('filter') }}"   name="filter" class="form-control" type="search" placeholder="{{ __lang('search') }}" aria-label="{{ __lang('search') }}" data-width="250">
            <button class="btn" type="submit"><i class="fas fa-search"></i></button>
        </div>
    </form>
@endsection

@section('content')
<div>
			<div >
                <a class="btn btn-primary" href="{{ adminUrl(array('controller'=>'lesson','action'=>'add')) }}"><i class="fa fa-plus"></i> {{ __lang('add-class') }}</a>
                <button class="btn btn-success"  data-toggle="collapse" href="#collapseFilter" role="button" aria-expanded="false" aria-controls="collapseFilter"><i class="fa fa-filter"></i> {{ __lang('filter') }}</button>

                <div class="collapse" id="collapseFilter">
                    <div class="card card-body">
                        <form id="filterform"   role="form"  method="get" action="{{ route('admin.lesson.index')  }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="sr-only" for="filter">{{ __lang('filter') }}</label>
                                        {{ formElement($text) }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="sr-only" for="group">{{ __lang('class-group') }}</label>
                                        {{ formElement($select) }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="sr-only" for="group">{{ __lang('sort') }}</label>
                                        {{ formElement($sortSelect) }}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> {{ __lang('filter') }}</button>
                                    <button type="button" onclick="$('#filterform input, #filterform select').val(''); $('#filterform').submit();" class="btn btn-success"><i class="fa fa-redo"></i> {{ __lang('clear') }}</button>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>


                <br><br>
                <div class="card">
					<div class="card-body">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>{{ __lang('id') }}</th>
									<th>{{ __lang('name') }}</th>
                                    <th>{{ __lang('class-type') }}</th>
									<th>{{ __lang('sort-order') }}</th>
                                    @php if(GLOBAL_ACCESS): @endphp
                                    <th>{{ __lang('created-by') }}</th>
                                    @php endif;  @endphp
									<th class="text-right1" >{{__lang('actions')}}</th>
								</tr>
							</thead>
							<tbody>
                            @php foreach($paginator as $row):  @endphp
								<tr>
									<td><span class="label label-success">{{ $row->id }}</span></td>
								  	<td>{{ $row->name }}</td>
                                    <td>{{ ($row->type=='s')? __lang('physical-location'):__lang('online') }}
                                    @php if($row->type=='c'): @endphp
                                        ( <a style="text-decoration: underline" href="{{ adminUrl(array('controller'=>'lecture','action'=>'index','id'=>$row->id)) }}">{{ $lectureTable->getTotalLectures($row->id) }} {{ __lang('lectures') }}</a> )
                                        @php endif;  @endphp
                                    </td>

                                    <td>{{ $row->sort_order }}</td>
                                    @php if(GLOBAL_ACCESS): @endphp
                                        <td>{{ adminName($row->admin_id) }}</td>
                                    @php endif;  @endphp

									<td class="text-right1">

                                        <div class="dropdown d-inline mr-2">
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-cogs"></i> {{ __lang('Actions') }}
                                            </button>
                                            <ul class="dropdown-menu wide-btn" role="menu">

                                                <a class="dropdown-item"  href="{{ adminUrl(array('controller'=>'lesson','action'=>'edit','id'=>$row->id)) }}"   ><i class="fa fa-edit"></i> {{ __lang('edit') }}</a>
                                                @php if($row->type == 'c'): @endphp
                                                <a class="dropdown-item"  href="{{ adminUrl(array('controller'=>'lecture','action'=>'index','id'=>$row->id)) }}"    >  <i class="fa fa-desktop"></i> {{ __lang('manage-lectures') }}</a>
                                                @php endif;  @endphp
                                                <a class="dropdown-item"  href="{{ adminUrl(array('controller'=>'lesson','action'=>'files','id'=>$row->id)) }}"    ><i class="fa fa-download"></i> {{ __lang('manage-downloads') }}</a>
                                                <a class="dropdown-item"  href="{{ adminUrl(array('controller'=>'lesson','action'=>'duplicate','id'=>$row->id)) }}"  ><i class="fa fa-copy"></i> {{ __lang('duplicate') }}</a>
                                                <a class="dropdown-item"  onclick="return confirm('{{__lang('delete-confirm')}}')" href="{{ adminUrl(array('controller'=>'lesson','action'=>'delete','id'=>$row->id)) }}"    ><i class="fa fa-trash"></i> {{ __lang('delete') }}</a>


                                            </ul>
                                        </div>





                                    </td>
								</tr>
								  @php endforeach;  @endphp

							</tbody>
						</table>

                        {{ $paginator->appends(request()->input())->links() }}

					</div><!--end .box-body -->
				</div><!--end .box -->
			</div><!--end .col-lg-12 -->
		</div>


        <!-- START SIMPLE MODAL MARKUP --><!-- /.modal -->
<!-- END SIMPLE MODAL MARKUP -->


@endsection
