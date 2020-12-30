<?php

namespace App\Exceptions;

use Dotenv\Exception\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param Throwable $exception
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        // Customize error code for Token verification with Google Service
        if ($exception instanceof \Google_Service_Exception){
            $returnErrorData['error'] = 7;
            Log::error('Google Service Error: '.$exception);
            return response()->json($returnErrorData, 503);
        }

        if ($request->wantsJson()){
            return $this->handleApiException($request, $exception);
        } else {
            $returnParent = parent::render($request, $exception);
        }

        return $returnParent;
    }

    /**
     * @param $request
     * @param \Exception $exception
     * @return \Illuminate\Http\JsonResponse
     */
    private function handleApiException($request, Throwable $exception): \Illuminate\Http\JsonResponse
    {
        $exception = $this->prepareException($exception);

        if ($exception instanceof HttpResponseException){
            $exception = $exception->getResponse();
        }

        if ($exception instanceof AuthenticationException){
            $exception = $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof ValidationException){
            $exception = $this->convertValidationExceptionToResponse($exception, $request);
        }

        return $this->customApiResponse($exception);
    }

    /**
     * @param $exception
     * @return \Illuminate\Http\JsonResponse
     */
    private function customApiResponse($exception): \Illuminate\Http\JsonResponse
    {
        if (method_exists($exception, 'getStatusCode')){
            $statusCode = $exception->getStatusCode();
            $statusCode = ($statusCode == 401) ? 403 : 401;
        }elseif ($exception->statusCode() == 401 && $exception->getCode() == 4){
            $statusCode = 401;
        } else{
            $statusCode = 500;
        }

        $response = [];

        switch ($statusCode){
            case 401:
                $response['error'] = 5; // API wrong key
                break;
            case 403:
                $response['error'] = 1; // expired / invalid token
                break;
            case 404:
                $response['error'] = 4; // invalid query
                break;
            case 405:
                $response['error'] = 3; // Wrong method
                break;
            default:
                $response['error'] = ($statusCode == 500) ? 0 : $exception->getMessage();
                break;
        }

        // For debugging
        /*if (config('app.debug')){
            $response['trace'] = $exception->getTrace();
            $response['code'] = $exception->getCode();
        }

        $response['status'] = $statusCode;*/

        return response()->json($response, $statusCode);
    }
}
