<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Headers\TraceparentHeader;
use App\Http\Middleware\SetJsonHeaders;
use App\Http\Middleware\ValidateTraceContext;
use App\Models\ApiService;
use App\Models\TestRun;
use App\Testing\Middlewares\RequestMiddleware;
use App\Testing\Middlewares\ResponseMiddleware;
use App\Testing\TestRequest;
use App\Testing\TestResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\AssertionFailedError;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SebastianBergmann\Timer\Timer;
use Throwable;

class TestController extends Controller
{
    /**
     * TestController constructor.
     */
    public function __construct()
    {
        $this->middleware([SetJsonHeaders::class, ValidateTraceContext::class]);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ApiService $apiService
     * @param string $uri
     * @return \Exception|AssertionFailedError|ResponseInterface|Throwable
     */
    public function __invoke(ServerRequestInterface $request, ApiService $apiService, string $uri)
    {
        $traceparent = new TraceparentHeader($request->getHeaderLine(TraceparentHeader::NAME));
        $testRun = TestRun::whereRaw('REPLACE(uuid, "-", "") = ?', $traceparent->getTraceId())->firstOrFail();

//        $testStep = $testRun->testSteps()
//            ->whereHas('source', function ($query) use ($apiService) {
//                $query->where('api_service_id', $apiService->id);
//            })
//            ->offset($testRun->testResults()
//                ->whereHas('testStep', function ($query) use ($apiService) {
//                    $query->whereHas('source', function ($query) use ($apiService) {
//                        $query->where('api_service_id', $apiService->id);
//                    });
//                })->count())
//            ->firstOrFail();

        $testStep = $testRun->testSteps()->offset($testRun->testResults()->count())->firstOrFail();

        $uri = (new Uri($uri));
        $request = $request->withUri($uri);

        $stack = new HandlerStack();
        $stack->setHandler(new CurlHandler());

        foreach ($testStep->testRequestSetups()->get() as $testRequestSetup) {
            $stack->push(new RequestMiddleware($testRequestSetup));
        }

        foreach ($testStep->testResponseSetups()->get() as $testResponseSetup) {
            $stack->push(new ResponseMiddleware($testResponseSetup));
        }

        Timer::start();
        $testResult = $testRun->testResults()->create([
            'test_step_id' => $testStep->id,
            'request' => new TestRequest($request),
        ]);

        try {
            $response = (new Client(['handler' => $stack, 'http_errors' => false]))->send($request);
            $testResult->update([
                'response' => new TestResponse($response),
            ]);

            $testRun->increment('duration', floor(Timer::stop() * 1000));
            return $this->doTest($testResult);
        } catch (RequestException $e) {
            $testRun->increment('duration', floor(Timer::stop() * 1000));
            return $e;
        }
    }
}
