<?php declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Http\Response;
use Inertia\Inertia;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use function Psy\debug;

class Handler extends ExceptionHandler
{
    /**
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * @param \Illuminate\Http\Request $request
     * @param Throwable $e
     * @return \Illuminate\Http\JsonResponse|Response|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);

        if (!$this->isHttpException($e) && config('app.debug')) {
            return $response;
        }

        if (in_array($response->status(), [
            400,
            401,
            403,
            404,
            419,
            429,
            500,
            503,
        ]) && !$request->expectsJson()) {
            return Inertia::render('error', [
                'status' => $response->status(),
            ])->toResponse($request);
        }

        return $response;
    }
}
