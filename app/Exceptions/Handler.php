<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
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
        $this->renderable(function (\Exception $e) {
            // if ($e->getPrevious() instanceof \Illuminate\Session\TokenMismatchException) {
            //     return redirect()->guest('/');
            // };
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        $this->renderable(function (\Exception $e) {
            if ($this->isHttpException($e)) {
                switch ($e->getStatusCode()) {
                    case 404:
                        return redirect()->guest('/');
                        break;
                        // internal error
                    case '500':
                        return redirect()->guest('/');
                        break;

                    default:
                        return $this->renderHttpException($e);
                        break;
                }
            }
        });
        return parent::render($request, $exception);
    }
}
