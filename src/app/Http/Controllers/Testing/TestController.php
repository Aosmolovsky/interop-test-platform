<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Headers\TraceparentHeader;
use App\Http\Middleware\SetJsonHeaders;
use App\Http\Middleware\ValidateTraceContext;
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
     * @param string $uri
     * @return \Exception|AssertionFailedError|ResponseInterface|Throwable
     */
    public function __invoke(ServerRequestInterface $request, string $uri)
    {
        $traceparent = new TraceparentHeader($request->getHeaderLine(TraceparentHeader::NAME));
        $testRun = TestRun::whereRaw('REPLACE(uuid, "-", "") = ?', $traceparent->getTraceId())->firstOrFail();
        $testStep = $testRun->testSteps()->offset($testRun->testResults()->count())->firstOrFail();

        $uri = (new Uri($uri));
        $request = $request->withUri($uri);

        $stack = new HandlerStack();
        $stack->setHandler(new CurlHandler());

//        foreach ($testStep->testRequestSetups()->get() as $testRequestSetup) {
//            $stack->push(new RequestMiddleware($testRequestSetup));
//        }
//
//        foreach ($testStep->testResponseSetups()->get() as $testResponseSetup) {
//            $stack->push(new ResponseMiddleware($testResponseSetup));
//        }

        $testResult = $testRun->testResults()->create([
            'test_step_id' => $testStep->id,
            'request' => new TestRequest($request),
        ]);

        try {
            $response = (new Client(['handler' => $stack, 'http_errors' => false]))->send($request);
            $testResult->response = new TestResponse($response);
            $this->doTest($testResult);
            $testResult->complete();

            return $response;
        } catch (RequestException $e) {
            $testResult->complete();
            return $e;
        }
    }
}
