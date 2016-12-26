<?php

namespace Katniss\Exceptions;

use Exception;
use ReflectionException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Katniss\Everdeen\Http\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Katniss\Everdeen\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }

    #region Extending functionality
    /**
     * Override
     *
     * @param Exception $e
     * @return Exception|NotFoundHttpException
     */
    protected function prepareException(Exception $e)
    {
        $e = parent::prepareException($e);
        if ($e instanceof MethodNotAllowedHttpException
            || $e instanceof ReflectionException
        ) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        return $e;
    }

    /**
     * Override
     *
     * @param HttpException $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(HttpException $e)
    {
        // firstly, try to render error by theme
        $checkPath = request()->getUrlPathInfo();
        if (!$checkPath->api && !$checkPath->webApi && $checkPath->locale) {
            $response = $this->renderHttpExceptionByTheme($e, $checkPath->admin);
            if (!empty($response->getContent())) {
                return $response;
            }
        }

        return parent::renderHttpException($e);
    }

    /**
     * Get response of error by theme's rendering
     *
     * @param HttpException $e
     * @param bool $isAdmin
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpExceptionByTheme(HttpException $e, $isAdmin = false)
    {
        $requestPath = $isAdmin ? adminUrl('errors/{code}', ['code' => $e->getStatusCode()])
            : homeUrl('errors/{code}', ['code' => $e->getStatusCode()]);
        $kernel = app()->make(\Illuminate\Contracts\Http\Kernel::class);
        $request = SymfonyRequest::create(
            $requestPath,
            'GET',
            [
                'message' => $e->getMessage(),
                'headers' => $e->getHeaders(),
                'original_path' => request()->path(),
            ],
            request()->cookies->all()
        );
        $request = Request::createFromBase($request);
        $response = $kernel->handle($request);
        $kernel->terminate($request, $response);
        return $response;
    }
    #endregion

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Katniss\Everdeen\Http\Request $request
     * @param  \Illuminate\Auth\AuthenticationException $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }
}
