<?php

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller;
use App\Http\Middleware\SetJsonHeaders;
use App\Http\Middleware\ValidateTraceContext;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ServerRequestInterface;

class MojaloopController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['api', ValidateTraceContext::class, SetJsonHeaders::class]);
    }

    public function quotes(ServerRequestInterface $request)
    {
        $client = new Client();
        $request = $request->withUri(new Uri('http://quoting-service.mojaloop.staging.s4.justcoded.com/quotes'));

        try {
            $response = $client->send($request);
            return $response;
        } catch (RequestException $e) {
            return $e->getResponse() ?: $e;
        }
    }

    public function quotesCallback(ServerRequestInterface $request)
    {
        return [];
    }
}
