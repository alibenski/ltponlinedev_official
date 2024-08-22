<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Session\TokenMismatchException;

use Mail;
use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use App\Mail\ExceptionOccured;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [];

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

    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     * 
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        if ($this->shouldReport($exception)) {
            $this->sendEmail($exception); // sends an email
        }
        parent::report($exception);
    }

    /**
     * Sends an email to the developer about the exception.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function sendEmail(Throwable $exception)
    {
        try {
            // $e = FlattenException::create($exception);

            // $handler = new HtmlErrorRenderer();
            // $css = $handler->getStylesheet();
            // $content = $handler->getBody($e);

            $content['message'] = $exception->getMessage();
            $content['file'] = $exception->getFile();
            $content['line'] = $exception->getLine();
            $content['trace'] = $exception->getTrace();

            $content['url'] = request()->url();
            $content['body'] = request()->all();
            $content['ip'] = request()->ip();

            Mail::to('allyson.frias@un.org')->send(new ExceptionOccured($content));
        } catch (Throwable $ex) {
            dd($ex);
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof \Illuminate\Http\Exceptions\PostTooLargeException) {

            return abort(405, 'File(s) too large. Please go back and upload accordingly');
        }


        if ($exception instanceof TokenMismatchException) {
            $request->session()->flash('expired', 'You have been logged out. Forms not submitted due to token mismatch error.');
            return redirect()
                ->route('login')
                ->withInput($request->except('password', 'password_confirmation', '_token'));
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            abort(405, 'MethodNotAllowedHttpException.');
        }

        return parent::render($request, $exception);
    }
}
