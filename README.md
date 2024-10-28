# Laravel Developer Showdown - Arief Fitranto

This repository contains the solution for the Laravel Developer Showdown coding challenge.

## Instructions

The full instructions for the challenge can be found here:

[https://invented-egret-e3d.notion.site/Laravel-Developer-Showdown-ARIEFFITRANTO-12c83dead18b80fea814e497e04b83a4](https://invented-egret-e3d.notion.site/Laravel-Developer-Showdown-ARIEFFITRANTO-12c83dead18b80fea814e497e04b83a4)

## Setup and Preparation

The following steps were taken to set up the project:

1.  **Create a new Laravel project:**
    ```bash
    laravel new user-update-test
    cd user-update-test
    ```

2.  **Initialize a Git repository:**
    ```bash
    git init
    ```

3.  **Add `timezone` field to the `users` table:**
    *   Create a migration:
        ```bash
        php artisan make:migration add_timezone_to_users_table --table=users
        ```
    *   In the migration file, add the `timezone` column:
        ```php
        public function up()
        {
            Schema::table('users', function (Blueprint $table) {
                $table->string('timezone')->nullable(); 
            });
        }
        ```
    *   Run the migration:
        ```bash
        php artisan migrate
        ```

4.  **Seed the database:**
    *   Update the `DatabaseSeeder` to create 20 users with random timezones:
        ```php
        use App\Models\User;

        public function run()
        {
            User::factory(20)->create()->each(function ($user) {
                $timezones = ['CET', 'CST', 'GMT+1'];
                $user->timezone = $timezones[array_rand($timezones)];
                $user->save();
            });
        }
        ```
    *   Run the seeder:
        ```bash
        php artisan db:seed
        ```

5.  **Create an artisan command to update user attributes:**
    *   Generate the command:
        ```bash
        php artisan make:command UpdateUserAttributes
        ```

## User Update Service

This service handles the updating of user attributes to a third-party API. It is designed to efficiently manage updates while adhering to API rate limits.

### Functionality

*   **Batch Updates:** Groups user updates into batches of 1000 to minimize API requests.
*   **Timezone Mapping:** Converts timezone abbreviations stored in the database to full timezone identifiers expected by the API.
*   **API Interaction:** Uses Laravel's HTTP client to make `POST` requests to the API endpoint.
*   **Logging:** Logs successful updates and errors to a dedicated log file (`storage/logs/userupdates.log`).
*   **Error Handling:** Includes basic error handling and returns appropriate API responses.
*   **Static Response for Testing:** Can be configured to use a static response for testing purposes when the actual API endpoint is not available.

### Usage

The `UserUpdateService` is triggered by the `UserObserver` whenever a user's `firstname`, `lastname`, or `timezone` attributes are updated.

**To manually trigger updates:**

1.  Resolve an instance of the service:

    ```php
    $userUpdateService = resolve(App\Services\UserUpdateService::class);
    ```

2.  Call the `updateUsers()` method with an array of users:

    ```php
    $userUpdateService->updateUsers([$user1, $user2, ...]);
    ```
3.  Or just run UpdateUserAttributes on Php Artisan:

    ```php
    php artisan user:update-user-attributes
    ```

### Configuration

*   **API Endpoint:** Set the actual API endpoint URL in the `updateBatch()` method.
*   **Timezone Mappings:** Add or modify timezone mappings in the `$timezoneMappings` array as needed.
*   **Static Response:**
    *   Set `USE_STATIC_API_RESPONSE=true` in your `.env` file to use the static response for testing.
    *   Set `USE_STATIC_API_RESPONSE=false` in your `.env` file to use the actual API endpoint.
*   **Set API:**
    *   Set `API_ENDPOINT` in your `.env` file to set API endpoint for testing.

### Dependencies

*   Laravel's HTTP Client
*   Guzzle HTTP Client

### Future Improvements

*   **More Robust Error Handling:** Implement more advanced error handling, including retry mechanisms with exponential backoff and handling of specific API error codes.
*   **Queueing:** Integrate with Laravel Queues to process updates asynchronously and improve performance.
*   **Rate Limiting:** Add specific handling for API rate limiting to prevent exceeding the allowed request rate.
*   **Unit Tests:** Write unit tests to cover the service's functionality and ensure code quality.

## Other details

*   **Branch:** The main feature is developed on the `feature/user-updates` branch.
*   **API Documentation:** The API documentation provided in the instructions is included in the `readme.md` file for reference.

## How to run

1.  Clone the repository: `git clone [your-repository-url]`
2.  Install dependencies: `composer install`
3.  Run the command to update user attributes: `php artisan user:update-user-attributes`
4.  Check the `storage/logs/userupdates.log` file for the output.
