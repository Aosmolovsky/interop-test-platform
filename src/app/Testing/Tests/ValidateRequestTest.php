<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Models\TestScript;
use App\Testing\TestCase;
use App\Testing\TestRequest;

class ValidateRequestTest extends TestCase
{
    /**
     * @var TestScript
     */
    protected $script;

    /**
     * @var TestRequest
     */
    protected $request;

    /**
     * @param TestScript $script
     * @param TestRequest $request
     */
    public function __construct(TestScript $script, TestRequest $request)
    {
        $this->script = $script;
        $this->request = $request;
    }

    /**
     * @return void
     */
    public function doTest()
    {
        $this->assertValidationPassed($this->request->toArray(), $this->script->rules);
    }

    /**
     * @return TestScript
     */
    public function getScript(): TestScript
    {
        return $this->script;
    }
}
