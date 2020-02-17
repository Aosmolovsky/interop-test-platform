@extends('layouts.app')

@section('title', __('Session info'))

@section('content')
    @include('sessions.register.includes.header')
    <div class="row d-flex justify-content-center">
        <div class="col">
            <form action="{{ route('sessions.register.information.store') }}" method="POST">
                @csrf
                <div class="card">
                    <div class="row">
                        <div class="col-6">
                            <div class="card-header border-0">
                                <h3 class="card-title">@yield('title')</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="form-label">
                                        {{ __('Name') }}
                                    </label>
                                    <input name="name" value="{{ old('name') }}" type="text" class="form-control @error('name') is-invalid @enderror">
                                    @error('name')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">
                                        {{ __('SUT') }}
                                    </label>
                                    <input type="text" disabled class="form-control" value="Service Provider">
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card-header border-0">
                                <h3 class="card-title">{{ __('Select use cases') }}</h3>
                            </div>
                            <div class="card-body pl-0">
                                <ul class="list-group overflow-auto" style="height: 320px">
                                    @foreach($operations as $operation)
                                        <li class="list-group-item">
                                            <b class="dropdown-toggle" v-b-toggle.suite-{{ $operation->id }}>
                                                {{ $operation->name }}
                                            </b>
                                            @if ($operation->positiveCases->count())
                                                <b-collapse id="suite-{{ $operation->id }}" visible>
                                                    <ul class="list-group">
                                                        <li class="list-group-item border-0 py-0">
                                                            <span class="d-inline-block dropdown-toggle py-2 font-weight-medium" v-b-toggle.positive-cases-{{ $operation->id }}>
                                                                {{ __('Happy flow') }}
                                                            </span>
                                                            <b-collapse id="positive-cases-{{ $operation->id }}" visible>
                                                                <ul class="list-group">
                                                                    @foreach($operation->positiveCases as $case)
                                                                        <li class="list-group-item">
                                                                            <label class="custom-control custom-checkbox mb-0">
                                                                                <input name="cases[{{ $case->id }}]" value="{{ $case->id }}" type="checkbox" class="custom-control-input" {{ old("cases.{$case->id}") ? 'checked' : '' }}>
                                                                                <span class="custom-control-label">
                                                                                    {{ $case->name }}
                                                                                </span>
                                                                            </label>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </b-collapse>
                                                        </li>
                                                    </ul>
                                                </b-collapse>
                                            @endif

                                            @if ($operation->negativeCases->count())
                                                <b-collapse id="suite-{{ $operation->id }}" visible>
                                                    <ul class="list-group">
                                                        <li class="list-group-item border-0 py-0">
                                                            <span class="d-inline-block dropdown-toggle py-2 font-weight-medium" v-b-toggle.negative-cases-{{ $operation->id }}>
                                                                {{ __('Unhappy flow') }}
                                                            </span>
                                                            <b-collapse id="negative-cases-{{ $operation->id }}" visible>
                                                                <ul class="list-group">
                                                                    @foreach($operation->negativeCases as $case)
                                                                        <li class="list-group-item">
                                                                            <label class="custom-control custom-checkbox mb-0">
                                                                                <input name="cases[{{ $case->id }}]" value="{{ $case->id }}" type="checkbox" class="custom-control-input" {{ old("cases.{$case->id}") ? 'checked' : '' }}>
                                                                                <span class="custom-control-label">
                                                                                    {{ $case->name }}
                                                                                </span>
                                                                            </label>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </b-collapse>
                                                        </li>
                                                    </ul>
                                                </b-collapse>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                                @error('cases')
                                    <div class="text-danger small mt-3">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('sessions.register.configuration.create') }}" class="btn btn-outline-primary">{{ __('Back') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
