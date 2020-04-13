@extends('layouts.sessions.test-case', [$session, $testCase])

@section('title', $session->name)

@section('content')
    <form class="card" action="{{ route('sessions.test-cases.test-data.store', [$session, $testCase]) }}" method="POST">
        @csrf
        <div class="card-header">
            <h3 class="card-title">
                <b>{{ __('Create test data') }}</b>
            </h3>
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
                    {{ __('Method') }}
                </label>
                <select name="request[method]" class="form-control custom-select @error('request.method') is-invalid @enderror">
                    @foreach(\App\Enums\HttpMethodEnum::values() as $value)
                        <option value="{{ $value }}" @if($value == old('request.method')) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
                @error('request.method')
                <span class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">
                    {{ __('URI') }}
                </label>
                <input name="request[uri]" value="{{ old('request.uri') }}" type="text" class="form-control @error('request.uri') is-invalid @enderror">
                @error('request.uri')
                <span class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">
                    {{ __('Headers') }}
                </label>
                <web-editor name="request[headers]" editor-class="@error('request.headers') is-invalid @enderror" :options='@json(['mode' => 'ace/mode/json'])'>
                    <template v-slot:content>{{ old('request.headers') }}</template>
                    <template v-slot:validation>
                        @error('request.headers')
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </template>
                </web-editor>
            </div>
            <div class="form-group">
                <label class="form-label">
                    {{ __('Body') }}
                </label>
                <web-editor name="request[body]" editor-class="@error('request.body') is-invalid @enderror" :options='@json(['mode' => 'ace/mode/json'])'>
                    <template v-slot:content>{{ old('request.body') }}</template>
                    <template v-slot:validation>
                        @error('request.body')
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </template>
                </web-editor>
            </div>
        </div>
        <div class="card-footer text-right">
            <a href="{{ route('sessions.test-cases.test-data.index', [$session, $testCase]) }}" class="btn btn-link">{{ __('Cancel') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
        </div>
    </form>
@endsection
