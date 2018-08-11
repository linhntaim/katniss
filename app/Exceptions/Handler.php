<?php

namespace Katniss\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use ReflectionException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
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
     * @param  \Illuminate\Http\Request $request
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
     * @param \Symfony\Component\HttpKernel\Exception\HttpException $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(HttpException $e)
    {
        // firstly, try to render error by theme
        $response = $this->renderHttpExceptionByTheme($e);
        if ($response !== false) {
            return $response;
        }

        return parent::renderHttpException($e);
    }

    /**
     * Get response of error by theme's rendering
     *
     * @param HttpException $e
     * @return \Symfony\Component\HttpFoundation\Response|bool
     */
    protected function renderHttpExceptionByTheme(HttpException $e)
    {
        $request = request();

        if (!$request->checkUrlPathInfo()) {
            $request->resolveUrlPathInfo();
        }
        $checkPath = $request->getUrlPathInfo();
        if ($checkPath->api || $checkPath->webApi || !$checkPath->locale) {
            return false;
        }

        $requestPath = $checkPath->admin ? adminUrl('errors/{code}', ['code' => $e->getStatusCode()])
            : homeUrl('errors/{code}', ['code' => $e->getStatusCode()]);

        return redirect($requestPath . '?' . http_build_query([
                'message' => $e->getMessage(),
                'headers' => $e->getHeaders(),
                'original_path' => request()->path(),
            ]));
    }
    #endregion
}
