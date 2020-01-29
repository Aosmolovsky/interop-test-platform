@extends('layouts.auth')

@section('title', __('Forgot password'))
@section('content')
    @if (session('status'))
        @component('components.alert', ['type' => 'success'])
            {{ session('status') }}
        @endcomponent
    @endif
    <form class="card" action="{{ route('password.email') }}" method="POST">
        @csrf
        <div class="card-body p-6">
            <div class="card-title">@yield('title')</div>
            <p class="text-muted">
                {{ __('Enter your email address and your password will be reset and emailed to you.') }}
            </p>
            <div class="form-group">
                <label class="form-label">
                    {{ __('Email') }}
                </label>
                <input name="email" value="{{ old('email') }}" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('e.g., :value', ['value' => 'john.doe@email.com']) }}">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-footer">
                <button type="submit" class="btn btn-primary btn-block">
                    {{ __('Send me password reset link') }}
                </button>
            </div>
        </div>
    </form>
    @guest
        <div class="text-center text-muted">
            {{ __('Forget it') }}, <a href="{{ route('login') }}">{{ __('send me back') }}</a> {{ __('to the sign in screen') }}.
        </div>
    @endguest
@endsection
