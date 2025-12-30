<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class DebugHelper
{
    /**
     * Log info message
     */
    public static function info(string $message, array $context = [])
    {
        Log::info($message, $context);
    }

    /**
     * Log error message
     */
    public static function error(string $message, array $context = [])
    {
        Log::error($message, $context);
    }

    /**
     * Log warning message
     */
    public static function warning(string $message, array $context = [])
    {
        Log::warning($message, $context);
    }

    /**
     * Log debug message
     */
    public static function debug(string $message, array $context = [])
    {
        Log::debug($message, $context);
    }

    /**
     * Log API request
     */
    public static function logRequest(string $method, string $endpoint, array $data = [])
    {
        Log::info("API Request: {$method} {$endpoint}", [
            'method' => $method,
            'endpoint' => $endpoint,
            'data' => $data,
            'user_id' => auth()->id() ?? 'guest',
            'ip' => request()->ip(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Log API response
     */
    public static function logResponse(string $endpoint, int $statusCode, $data = null)
    {
        Log::info("API Response: {$endpoint}", [
            'endpoint' => $endpoint,
            'status_code' => $statusCode,
            'data' => $data,
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Log database query
     */
    public static function logQuery(string $query, array $bindings = [])
    {
        Log::debug("Database Query", [
            'query' => $query,
            'bindings' => $bindings,
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Log exception
     */
    public static function logException(\Throwable $exception, array $context = [])
    {
        Log::error("Exception: {$exception->getMessage()}", [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'context' => $context,
            'timestamp' => now()->toDateTimeString(),
        ]);
    }
}
