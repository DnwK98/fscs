<?php

namespace App\Exceptions;

use App\Http\Responses\BaseResponse;
use App\Http\Responses\InternalServerErrorResponse;
use App\Http\Responses\MethodNotAllowedHttpResponse;
use App\Http\Responses\NotFoundResponse;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

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
     * @throws Exception
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
     * @return BaseResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception)
    {
        if(\App::environment() != 'local') {
            return new InternalServerErrorResponse(500);
        } else {
            return parent::render($request, $exception);
        }
    }

    /**
     * @param HttpExceptionInterface $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(HttpExceptionInterface $e)
    {
        if(\App::environment() == 'local') {
            return parent::renderHttpException($e);
        }

        $status = $e->getStatusCode();
        if($status === 404){
            return new NotFoundResponse();
        }
        if($status === 405 ){
            return new MethodNotAllowedHttpResponse($e->getHeaders()['Allow'] ?? "");
        }
        return new InternalServerErrorResponse($e->getStatusCode());
    }
}
