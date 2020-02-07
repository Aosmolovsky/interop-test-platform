<?php

namespace App\Testing;

use Illuminate\Http\Request;

final class RequestTest extends TestCase
{
    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct('doRun', [$request]);
    }

    /**
     * @param Request $request
     * @return void
     */
    public function doRun(Request $request)
    {
        $this->assertValidationPasses([], ['title' => 'required|unique:posts|max:255']);
    }
}
