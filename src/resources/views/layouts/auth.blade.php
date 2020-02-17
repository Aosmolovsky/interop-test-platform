@extends('layouts.base')

@push('styles')
    <link href="{{ mix('css/vendor.css', 'assets') }}" rel="stylesheet">
    <link href="{{ mix('css/app.css', 'assets') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ mix('js/app.js', 'assets') }}" defer></script>
@endpush

@section('page')
    <div class="page-single">
        <div class="container">
            <div class="row">
                <div class="col col-login mx-auto">
                    <div class="text-center mb-5">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('images/logo.png') }}" class="mb-2" alt="{{ config('app.name')  }}">
                        </a>
                        <div class="text-primary">
                            <h1 class="col-login__title mb-1">{{ env('APP_COMPANY_LAB') }}</h1>
                            <h2 class="col-login__subtitle mb-0">{{ config('app.name')  }}</h2>
                        </div>
                    </div>
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
@endsection
