<div class="card">
    <div class="card-header flex-column align-items-start h-100 border-bottom py-4">
        <div class="d-flex align-items-center w-100 mb-2">
            @include('sessions.includes.runs-progress', $session)
        </div>
        <h2 class="card-title w-100 text-truncate">
            <b>{{ $session->name }}</b>
        </h2>
        <p class="mb-0">
            {{ \Illuminate\Support\Str::limit($session->description) }}
        </p>
    </div>
    <div class="card-body flex-shrink-0 py-4">
        <ul class="list-unstyled">
            <li>
                <i class="fe fe-briefcase"></i>
                {{ $session->testCases->unique('use_case_id')->count() }}
            </li>
            <li>
                <i class="fe fe-file-text"></i>
                {{ $session->testCases->count() }}
            </li>
            @if($session->lastTestRun)
                <li>
                    <i class="fe fe-calendar"></i>
                    {{ $session->lastTestRun->completed_at->diffForHumans() }}
                </li>
            @endif
        </ul>
    </div>
</div>
