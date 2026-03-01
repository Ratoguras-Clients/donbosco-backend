<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait RevalidatesNextJs
{
    /**
     * Send a revalidation request to Next.js for specific paths.
     */
    protected function revalidatePaths(array|string $paths): void
    {
        $url = config('services.nextjs.url');
        $secret = config('services.nextjs.revalidate_secret');

        if (!$url || !$secret) {
            Log::warning('Next.js revalidation skipped: URL or Secret not configured.');
            return;
        }

        $endpoint = rtrim($url, '/') . '/api/revalidate';
        $pathsArray = (array) $paths;

        try {
            $response = Http::post($endpoint, [
                'paths'  => $pathsArray,
                'secret' => $secret,
            ]);

            if (!$response->successful()) {
                Log::error('Next.js revalidation failed.', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                    'paths'  => $pathsArray
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Next.js revalidation exception: ' . $e->getMessage());
        }
    }
}