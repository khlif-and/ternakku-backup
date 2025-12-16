<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Log;
use Throwable;

class ErrorHandler
{
    /**
     * Tangani error dengan logging + feedback otomatis.
     */
    public static function handle(callable $callback, string $context = 'General Error')
    {
        try {
            return $callback();
        } catch (\InvalidArgumentException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (Throwable $e) {
            Log::error("❌ {$context}", [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            $errorMessage = config('app.debug')
                ? $e->getMessage() . ' (file: ' . basename($e->getFile()) . ' line ' . $e->getLine() . ')'
                : 'Terjadi kesalahan pada sistem.';

            return back()->withInput()->with('error', $errorMessage);
        }
    }
}
