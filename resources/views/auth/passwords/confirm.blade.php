@extends('layouts.auth')
@section('content')

    <div class="card card-primary">
                <div class="card-header">{{ __lang('confirm-password') }}</div>

                <div class="card-body">
                    {{ __lang('please-confirm-password') }}

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="form-group  ">
                            <label for="password"  >{{ __lang('Password') }}</label>

                            <div  >
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group  mb-0">
                            <div  >
                                <button type="submit" class="btn btn-primary">
                                    {{ __lang('Confirm Password') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __lang('lost-password') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

@endsection
