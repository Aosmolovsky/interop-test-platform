<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Headers\TraceparentHeader;
use App\Http\Middleware\SetJsonHeaders;
use App\Models\TestPlan;
use App\Models\TestRun;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\AssertionFailedError;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class RunController extends Controller
{
    /**
     * RunController constructor.
     */
    public function __construct()
    {
        $this->middleware(['api', SetJsonHeaders::class]);
    }

    /**
     * @param ServerRequestInterface $request
     * @param TestPlan $plan
     * @param string $path
     * @return \Exception|AssertionFailedError|ResponseInterface|Throwable
     */
    public function __invoke(ServerRequestInterface $request, TestPlan $plan, string $path = '')
    {
        $step = $plan->steps()
            ->whereRaw('? like path', $path)
            ->where('method', $request->getMethod())
            ->firstOrFail();
        $run = TestRun::create([
            'case_id' => $plan->case_id,
            'session_id' => $plan->session_id,
        ]);

        $uri = (new Uri($step->platform->server))
            ->withPath($path);
        $traceparent = (new TraceparentHeader())
            ->withTraceId($run->trace_id)
            ->withVersion(TraceparentHeader::DEFAULT_VERSION);
        $request = $request->withUri($uri)
            ->withAddedHeader(TraceparentHeader::NAME, (string) $traceparent);

        return $this->doTest($request, $run, $step);
    }
}
