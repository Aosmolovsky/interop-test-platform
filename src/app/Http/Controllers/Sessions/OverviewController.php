<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Models\Session;
use Illuminate\Database\Eloquent\Builder;

class OverviewController extends Controller
{
    /**
     * OverviewController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $sessions = auth()->user()->sessions()
            ->when(request('q'), function (Builder $query, $q) {
                return $query->where('name', 'like', "%{$q}%");
            })
            ->when(request()->route()->hasParameter('trashed'), function (Builder $query, $trashed) {
                return $trashed ? $query->onlyTrashed() : $query->withoutTrashed();
            })
            ->latest()
            ->paginate();

        return view('sessions.index', compact('sessions'));
    }

    /**
     * @param Session $session
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Session $session)
    {
        $this->authorize('view', $session);
        $testRuns = $session->testRuns()
            ->latest()
            ->paginate();
        $useCases = $session->testCases->mapWithKeys(function ($item) {
            return [$item->useCase];
        });

        return view('sessions.show', compact('session', 'testRuns', 'useCases'));
    }

    /**
     * @param Session $session
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Session $session)
    {
        $session->delete();

        return redirect()
            ->back()
            ->with('success', __('Session deactivated successfully'));
    }

    /**
     * @param Session $sessionOnlyTrashed
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function restore(Session $sessionOnlyTrashed)
    {
        $this->authorize('restore', $sessionOnlyTrashed);
        $sessionOnlyTrashed->restore();

        return redirect()
            ->back()
            ->with('success', __('Session activated successfully'));
    }

    /**
     * @param Session $sessionWithTrashed
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function forceDestroy(Session $sessionWithTrashed)
    {
        $this->authorize('delete', $sessionWithTrashed);
        $sessionWithTrashed->forceDelete();

        return redirect()
            ->back()
            ->with('success', __('Session deleted successfully'));
    }
}
