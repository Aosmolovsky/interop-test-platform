<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Models\TestSession;

class HomeController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $sessions = auth()->user()->sessions()
            ->withoutTrashed()
            ->when(request('q'), function ($query, $q) {
                return $query->where('name', 'like', "%{$q}%");
            })
            ->latest()
            ->paginate();

        return view('sessions.index', compact('sessions'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function trash()
    {
        $this->authorize('viewAny', TestSession::class);
        $sessions = auth()->user()->sessions()
            ->onlyTrashed()
            ->when(request('q'), function ($query, $q) {
                return $query->where('name', 'like', "%{$q}%");
            })
            ->latest()
            ->paginate();

        return view('sessions.index', compact('sessions'));
    }

    /**
     * @param TestSession $session
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(TestSession $session)
    {
        $runs = $session->runs()
            ->with('case', 'session')
            ->whereNotNull('completed_at')
            ->latest()
            ->paginate();

        return view('sessions.show', compact('session', 'runs'));
    }

    /**
     * @param TestSession $session
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(TestSession $session)
    {
        $session->delete();

        return redirect()
            ->route('sessions.index')
            ->with('success', __('Session deactivated successfully'));
    }

    /**
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function restore(int $id)
    {
        $session = TestSession::onlyTrashed()
            ->findOrFail($id);
        $this->authorize('restore', $session);
        $session->restore();

        return redirect()
            ->route('sessions.index')
            ->with('success', __('Session activated successfully'));
    }
}
