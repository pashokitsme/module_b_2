<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof UnauthorizedHttpException)
            return response()->json(['success' => false, 'message' => 'Login failed'], 403);

        if ($e instanceof AccessDeniedHttpException)
            return response()->json(['success' => false, 'message' => 'Forbidden for you'], 403);

        if ($e instanceof ValidationException)
            return $e->response;

        if ($e instanceof NotFoundHttpException)
            return response()->json(['success' => false, "messsage" => $e->getMessage() ?: "Not Found"]);

        return parent::render($request, $e);
    }
}
