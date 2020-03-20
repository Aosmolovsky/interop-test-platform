@extends('layouts.session', $session)

@section('title', $session->name)

@section('session-sidebar')
    <div class="card mb-0">
        <div class="card-header px-4">
            <h3 class="card-title">{{ __('Select use cases') }}</h3>
        </div>
        <div class="card-body p-0">
            <ul class="list-unstyled">
                @foreach($useCases as $useCase)
                    <li>
                        <b class="d-block dropdown-toggle py-2 px-4 border-bottom" v-b-toggle.use-case-{{ $useCase->id }}>
                            {{ $useCase->name }}
                        </b>
                        @if ($session->positiveTestCases->where('use_case_id', $useCase->id)->count())
                            <b-collapse id="use-case-{{ $useCase->id }}" visible>
                                <ul class="list-unstyled">
                                    <li>
                                        <span class="dropdown-toggle d-block px-5 py-2 font-weight-medium border-bottom" v-b-toggle.positive-test-cases-{{ $useCase->id }}>
                                            {{ __('Happy flow') }}
                                        </span>
                                        <b-collapse id="positive-test-cases-{{ $useCase->id }}" visible>
                                            <ul class="list-unstyled">
                                                @foreach($session->positiveTestCases->where('use_case_id', $useCase->id) as $testCase)
                                                    <li class="list-group-item-action d-flex justify-content-between align-items-center px-6 py-2 border-bottom">
                                                        <a href="{{ route('sessions.test_cases.show', [$session, $testCase]) }}">{{ $testCase->name }}</a>
                                                        @if($lastRun = $session->testRuns()->latest()->where('test_case_id', $testCase->id)->first())
                                                            <span class="flex-shrink-0 mr-0 ml-1 status-icon @if($lastRun->isSuccessful()) bg-success @else bg-danger @endif"></span>
                                                        @else
                                                            <span class="flex-shrink-0 mr-0 ml-1 status-icon bg-secondary"></span>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </b-collapse>
                                    </li>
                                </ul>
                            </b-collapse>
                        @endif

                        @if ($session->negativeTestCases->where('use_case_id', $useCase->id)->count())
                            <b-collapse id="use-case-{{ $useCase->id }}" visible>
                                <ul class="list-unstyled">
                                    <li>
                                        <span class="dropdown-toggle d-block px-5 py-2 font-weight-medium border-bottom" v-b-toggle.negative-test-cases-{{ $useCase->id }}>
                                            {{ __('Unhappy flow') }}
                                        </span>
                                        <b-collapse id="negative-test-cases-{{ $useCase->id }}" visible>
                                            <ul class="list-unstyled">
                                                @foreach($session->negativeTestCases->where('use_case_id', $useCase->id) as $testCase)
                                                    <li class="list-group-item-action d-flex justify-content-between align-items-center px-6 py-2 border-bottom">
                                                        <a href="{{ route('sessions.test_cases.show', [$session, $testCase]) }}">{{ $testCase->name }}</a>
                                                        @if($lastRun = $session->testRuns()->latest()->where('test_case_id', $testCase->id)->first())
                                                            <span class="flex-shrink-0 mr-0 ml-1 status-icon @if($lastRun->isSuccessful()) bg-success @else bg-danger @endif"></span>
                                                        @else
                                                            <span class="flex-shrink-0 mr-0 ml-1 status-icon bg-secondary"></span>
                                                        @endif
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
        </div>
    </div>
@endsection

@section('session-content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        <b>{{ __('Latest test runs') }}</b>
                    </h2>
                </div>
                <div class="table-responsive mb-0">
                    <table class="table table-striped table-hover card-table">
                        <thead class="thead-light">
                        <tr>
                            <th class="text-nowrap w-auto">{{ __('Test Case') }}</th>
                            <th class="text-nowrap w-auto">{{ __('Run ID') }}</th>
                            <th class="text-nowrap w-auto">{{ __('Total') }}</th>
                            <th class="text-nowrap w-auto">{{ __('Passed') }}</th>
                            <th class="text-nowrap w-auto">{{ __('Failures') }}</th>
                            <th class="text-nowrap w-auto">{{ __('Date') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($testRuns as $testRun)
                            <tr>
                                <td>
                                    <a href="{{ route('sessions.test_cases.show', [$testRun->session, $testRun->testCase]) }}">
                                        {{ $testRun->testCase->name }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('sessions.test_cases.results', [$testRun->session, $testRun->testCase, $testRun]) }}">
                                        {{ $testRun->uuid }}
                                    </a>
                                </td>
                                <td>
                                    {{ $testRun->total }}
                                </td>
                                <td>
                                    {{ $testRun->successful }}
                                </td>
                                <td>
                                    {{ $testRun->unsuccessful }}
                                </td>
                                <td>
                                    {{ $testRun->created_at }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="6">
                                    {{ __('No Results') }}
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    @include('components.grid.pagination', ['paginator' => $testRuns])
                </div>
            </div>
        </div>
    </div>
@endsection
