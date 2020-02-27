<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\TestSession;
use App\Http\Controllers\Controller;

class SessionController extends Controller
{
    /**
     * SessionController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->authorizeResource(TestSession::class, 'session');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $sessions = TestSession::withoutTrashed()
            ->whereHas('owner', function ($query) {
                $query->when(request('q'), function ($query, $q) {
                    $query->whereRaw('CONCAT(first_name, " ", last_name) like ?', "%{$q}%")
                        ->orWhere('name', 'like', "%{$q}%");
                });
            })
            ->with('lastRun')
            ->latest()
            ->paginate();

        return view('admin.sessions.index', compact('sessions'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function trash()
    {
        $this->authorize('viewAny', TestSession::class);
        $sessions = TestSession::onlyTrashed()
            ->whereHas('owner', function ($query) {
                $query->when(request('q'), function ($query, $q) {
                    $query->whereRaw('CONCAT(first_name, " ", last_name) like ?', "%{$q}%")
                        ->orWhere('name', 'like', "%{$q}%");
                });
            })
            ->with('lastRun')
            ->latest()
            ->paginate();

        return view('admin.sessions.index', compact('sessions'));
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
            ->back()
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
            ->back()
            ->with('success', __('Session activated successfully'));
    }

    /**
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function forceDestroy(int $id)
    {
        $session = TestSession::withTrashed()
            ->findOrFail($id);
        $this->authorize('delete', $session);
        $session->forceDelete();

        return redirect()
            ->back()
            ->with('success', __('Session deleted successfully'));
    }
}
