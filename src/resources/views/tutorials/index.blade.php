@extends('layouts.default')

@section('title', __('Tutorials'))

@section('content')
    <h1 class="page-title mb-5">
        <b>@yield('title')</b>
    </h1>
    <div class="card">
        <p>test</p>
        @include('tutorials.includes.scenario-card')
        @include('tutorials.includes.scenario-accordion')
    </div>
    @push('scripts')
        <script src="{{ asset('assets/js/tutorials.js') }}" defer></script>
    @endpush
@endsection
