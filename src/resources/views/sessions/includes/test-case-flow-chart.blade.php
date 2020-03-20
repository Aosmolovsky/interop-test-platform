<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <b>{{ __('Use case flow') }}</b>
        </h2>
    </div>
    <div class="card-body bg-light">
        <flow-chart>
            sequenceDiagram;
            @foreach ($testCase->testSteps as $testStep)
                {{ $testStep->source->name }}->>{{ $testStep->target->name }}: {{ $testStep->name }}
            @endforeach
        </flow-chart>
    </div>
</div>
