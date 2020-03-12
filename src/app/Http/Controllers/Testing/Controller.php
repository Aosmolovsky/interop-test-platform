<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller as BaseController;
use App\Models\TestResult;
use App\Models\TestRun;
use App\Models\TestStep;
use App\Testing\Constraints\ValidationPasses;
use GuzzleHttp\Client;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SebastianBergmann\Timer\Timer;
use Throwable;

class Controller extends BaseController
{
    protected function doTest(TestResult $testResult)
    {
        dd($testResult);
//        $result = $run->results()->make([
//            'step_id' => $step->id,
//            'request' => $this->convertRequestToArray($request),
//        ]);
//        Timer::start();
//
//        try {
//            $response = (new Client(['http_errors' => false]))->send($request);
//            $result->response = $this->convertResponseToArray($response);
//            // Match test step
//            Assert::assertThat($result->request, new ValidationPasses($step->expected_request), __('Expected request:'));
//            Assert::assertThat($result->response, new ValidationPasses($step->expected_response), __('Expected response:'));
//            $result->pass();
//            return $response;
//        } catch (AssertionFailedError $exception) {
//            $result->fail($exception->getMessage());
//            return $response;
//        } catch (Throwable $exception) {
//            $result->error($exception->getMessage());
//            return $exception;
//        }
    }
}
