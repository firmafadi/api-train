<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;
use App\Traits\ApiResponse;

use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponse;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        if ($messageError = $this->errorException($e)) {
            return $messageError;
        } else {
            $request->headers->set('Accept', 'application/json');
            return parent::render($request, $e);
        }
    }

    protected function errorException(Throwable $e)
    {
        $error = null;
        if ($e instanceof AuthenticationException) {
            $error = $this->errorResponse('Unauthorized', 401);
        } elseif ($e instanceof ModelNotFoundException) {
            $error = $this->errorResponse('Object Not Found', 404);
        } elseif ($e instanceof NotFoundHttpException) {
            $error = $this->errorResponse('Url Not Found', 404);
        } elseif ($e instanceof HttpException) {
            $error = $this->errorResponse($e->getMessage(), $e->getStatusCode());
        } elseif ($e instanceof AuthorizationException) {
            $error = $this->errorResponse($e->getMessage(), 403);
        } elseif ($e instanceof ValidationException) {
            $error = $this->errorResponse(ucfirst(collect($e->errors())->first()[0]), 422);
        }
        return $error;
    }
    /**
     * errorResponse
     *
     * @param  mixed $message
     * @param  mixed $code
     * @return void
     */
    protected function errorResponse($message, $code)
    {
        return $this->setResponse(null, $code, $message);
    }
}
