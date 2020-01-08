<?php

namespace App\Exceptions;

use App\Http\Responses\BadRequestResponse;
use App\Http\Responses\BaseResponse;
use App\Http\Responses\InternalServerErrorResponse;
use App\Http\Responses\MethodNotAllowedHttpResponse;
use App\Http\Responses\NotFoundResponse;
use App\Http\Responses\UnauthorizedResponse;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        UnauthorizedException::class,
        AuthorizationException::class,
        AuthenticationException::class,
        ValidationException::class,
        HttpExceptionInterface::class
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
        if ($exception instanceof UnauthorizedException ||
            $exception instanceof AuthorizationException ||
            $exception instanceof AuthenticationException
        ) {
            return new UnauthorizedResponse();
        }

        if ($exception instanceof ValidationException) {
            return new BadRequestResponse($exception->errors());
        }

        if($exception instanceof HttpExceptionInterface){
            $status = $exception->getStatusCode();
            if($status === 404){
                return new NotFoundResponse();
            }
            if($status === 405 ){
                return new MethodNotAllowedHttpResponse($exception->getHeaders()['Allow'] ?? "");
            }
        }
        if(\App::environment() != 'local') {
            $debugToken = bin2hex(Str::random(10));
            $message = str_replace(array("\n", "\r"), ' ', $exception->__toString());
            Log::critical("[$debugToken] $message");
            return new InternalServerErrorResponse(500, $debugToken);
        } else {
            return parent::render($request, $exception);
        }
    }
}
