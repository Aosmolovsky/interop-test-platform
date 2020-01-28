<?php

namespace App\Http\Controllers\Sessions;

use App\Models\TestSession;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function showSelectionForm()
    {
        return view('sessions.register.select');
    }
}
