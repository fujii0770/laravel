<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Support\MessageBag;

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
        if ($exception instanceof PostTooLargeException) {
            $messageBag = new MessageBag(array("photo" => (trans('ebi.max_size')).ini_get("upload_max_filesize")."B"));
            return redirect()->back()->with("errors", $messageBag)->with([
                'message' => trans('crudbooster.alert_validation_error', ['error' => (trans('ebi.max_size')).ini_get("upload_max_filesize")."B"]),
                'message_type' => 'warning',
            ])->withInput();
        } else {
            return parent::render($request, $exception);
        }
    }
}
