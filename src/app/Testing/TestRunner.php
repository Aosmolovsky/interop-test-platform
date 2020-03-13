<?php declare(strict_types=1);

namespace App\Testing;

use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestResult;
use PHPUnit\Runner\TestHook;
use PHPUnit\Runner\TestListenerAdapter;

class TestRunner
{
    /**
     * @var TestHook[]
     */
    protected $extensions = [];

    /**
     * @param Test $test
     * @return TestResult
     */
    public function run(Test $test): TestResult
    {
        $result = $test->run($this->buildResult());

        return $result;
    }

    /**
     * @return TestResult
     */
    protected function buildResult(): TestResult
    {
        $result = new TestResult();
        $listener = new TestListenerAdapter();

        foreach ($this->extensions as $extension) {
            $listener->add($extension);
        }

        $result->addListener($listener);

        return $result;
    }

    /**
     * @param TestHook $extension
     */
    public function addExtension(TestHook $extension): void
    {
        $this->extensions[] = $extension;
    }
}
