<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Models\TestScript;
use App\Testing\TestCase;
use App\Testing\TestResponse;

class ValidateResponseTest extends TestCase
{
    /**
     * @var TestScript
     */
    protected $script;

    /**
     * @var TestResponse
     */
    protected $response;

    /**
     * @param TestScript $script
     * @param TestResponse $request
     */
    public function __construct(TestScript $script, TestResponse $response)
    {
        $this->script = $script;
        $this->response = $response;
    }

    /**
     * @return void
     */
    public function doTest()
    {
        $this->assertValidationPassed($this->response->toArray(), $this->script->rules);
    }

    /**
     * @return TestScript
     */
    public function getScript(): TestScript
    {
        return $this->script;
    }
}
