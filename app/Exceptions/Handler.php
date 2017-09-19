<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ValidationException) {
            return response()->json(
                [
                    'code' => 400,
                    'messages' => $exception->getMessages()
                ],
                400
            );
        }

        if ($exception instanceof ModelNotFoundException) {
            return JsonResponse::create(
                [
                'code' => 404,
                'messages' => 'Not Found'
                ],
                404
            );
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return JsonResponse::create(
                [
                    'code' => 400,
                    'messages' => 'Method not allowed.'
                ],
                400
            );
        }

        if ($exception instanceof UnauthorizedHttpException) {
            return JsonResponse::create(
                [
                    'code' => 401,
                    'messages' => 'Permission Denied'
                ],
                401
            );
        }

        if ($exception instanceof AuthenticationException) {
            return JsonResponse::create(
                [
                    'code' => 401,
                    'messages' => 'unauthorized'
                ],
                401
            );
        }

        if ($exception instanceof \Exception) {
            return JsonResponse::create(
                [
                    'code' => 500,
                    'messages' => 'An error has occurred, please contact the administrator'
                ],
                500

            );
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
