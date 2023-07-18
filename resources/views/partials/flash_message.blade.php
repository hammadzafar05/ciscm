@if (count($errors) > 0)
    <div class="alert alert-danger alert-dismissible show fade">
        <div class="alert-body">
            <button class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif


@foreach (['danger', 'warning', 'success', 'info'] as $msg)
    @if(Session::has('alert-' . $msg))

        <div class="alert alert-{{ $msg }} alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
                {!! clean(Session::get('alert-' . $msg)) !!}
            </div>
        </div>
    @endif
@endforeach
@if(Session::has('flash_message'))
    <div class="alert alert-success alert-dismissible show fade flash_message-1">
        <div class="alert-body">
            <button class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
            {!! clean(Session::get('flash_message')) !!}
        </div>
    </div>
@endif

@if(isset($flash_message))
    <div class="alert alert-success alert-dismissible show fade flash_message-2">
        <div class="alert-body">
            <button class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
            {!! clean($flash_message) !!}
        </div>
    </div>
@endif


@if(Session::has('flash_error_message'))
    <div class="alert alert-success alert-dismissible show fade flash_error_message-1">
        <div class="alert-body">
            <button class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
            {!! clean(Session::get('flash_error_message')) !!}
        </div>
    </div>
@endif
@if(isset($flash_error_message))
    <div class="alert alert-danger alert-dismissible show fade flash_error_message-2">
        <div class="alert-body">
            <button class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
            {!! clean($flash_error_message) !!}
        </div>
    </div>
@endif
