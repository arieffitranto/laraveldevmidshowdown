<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UserUpdateService
{
    protected $timezoneMappings = [
        'America/Los_Angeles' => 'PST',
        'Europe/Paris' => 'CET',
        'America/Chicago' => 'CST',
        'Europe/London' => 'GMT+1',
        // Add more mappings as needed
    ];

    public function updateUsers(array $users)
    {
        $batches = array_chunk($users, 1000);
        foreach ($batches as $batch) {
            $this->updateBatch($batch);
        }
    }

    private function updateBatch(array $users)
    {
        $reversedMappings = array_flip($this->timezoneMappings);

        $payload = [
            'batches' => [
                [
                    'subscribers' => collect($users)->map(function ($user) use ($reversedMappings) {
                        return [
                            'email' => $user->email, // Email is the primary key
                            'name' => $user->firstname . ' ' . $user->lastname, // Name is included
                            'time_zone' => $reversedMappings[$user->timezone] ?? $user->timezone,
                        ];
                    })->toArray(),
                ],
            ],
        ];

        // Switch between static response and API response
        if (!config('app.use_static_api_response')) { // Check the config setting
            $response = Http::response([
                'success' => true,
                'message' => 'Users updated successfully (simulated)',
                'data' => [
                    // Add any relevant data you want to simulate in the response
                ]
            ], 200); // Simulate a 200 OK response

            $guzzleResponse = $response->wait(); // Get the underlying response object
        } else {
            // Make the actual API call
            $guzzleResponse = Http::post('https://api.endpoint.com', [
                'json' => $payload,
            ]);
        }

        // Get the underlying response object
        $guzzleResponse = $response->wait();

        $userUpdatesLogger = Log::channel('userupdates');
        // Now you can access the status code
        if ($guzzleResponse->getStatusCode() == 200) {
            // Access the response body as an array
            $responseBody = json_decode($guzzleResponse->getBody()->getContents(), true);

            $userUpdatesLogger->info('User updates successful', [
                'response_status' => $guzzleResponse->getStatusCode(),
                'response_body' => $responseBody,
                'updated_users' => collect($users)->pluck('email')->toArray(),
            ]);
        } else {
            // This block won't be executed with the static 200 response

            // Access the response body as an array in the error block as well
            $responseBody = json_decode($guzzleResponse->getBody()->getContents(), true);

            $userUpdatesLogger->error('User updates failed', [
                'response_status' => $guzzleResponse->getStatusCode(),
                'response_body' => $responseBody,
                'failed_users' => collect($users)->pluck('email')->toArray(),
                'error_message' => $responseBody['error'] ?? 'Unknown error',
            ]);
        }
    }
}
