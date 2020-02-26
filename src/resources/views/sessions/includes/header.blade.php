<div class="row border-bottom">
    <div class="col">
        <div class="page-header m-0 pb-5">
            <h1 class="page-title">
                <b>{{ $session->name }}</b>
            </h1>
            <span class="badge badge-success ml-2 p-1">{{ __('Active') }}</span>
            <div class="ml-4 pt-1">
                {{ __('Execution') }}:
                <i class="fe fe-briefcase"></i>
                <small>{{ $session->suites_count }}</small>
                <i class="fe fe-file-text"></i>
                <small>{{ $session->cases_count }}</small>
            </div>
            <div class="col-2">
                <b-progress class="h-3 rounded-0">
                    <b-progress-bar :value="{{ $session->runs_count ? $session->pass_runs_count / $session->runs_count * 100 : 0 }}" variant="success"></b-progress-bar>
                    <b-progress-bar :value="{{ $session->runs_count ? $session->fail_runs_count / $session->runs_count * 100 : 0 }}" variant="danger"></b-progress-bar>
                </b-progress>
            </div>
            <a href="#" class="btn btn-outline-primary ml-4">{{ __('Deactivate') }}</a>
        </div>
    </div>
</div>
