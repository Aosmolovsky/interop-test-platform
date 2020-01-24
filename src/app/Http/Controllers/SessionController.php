<?php

namespace App\Http\Controllers;

use App\Models\TestSession;

class SessionController extends Controller
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
        $sessions = auth()->user()->sessions()->paginate();

        return view('sessions.index', compact('sessions'));
    }
}
