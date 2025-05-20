<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Sentry\State\HubInterface;

class Handler extends ExceptionHandler
{
    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     */
    public function report(Throwable $exception): void
    {
        // Kirim ke Sentry jika exception ini seharusnya dilaporkan
        if ($this->shouldReport($exception) && app()->bound('sentry')) {
            \Sentry\captureException($exception);
        }

        parent::report($exception);
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Kamu bisa log custom error di sini jika mau
        });
    }
}
