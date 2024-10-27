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
    *   In the command file, implement the logic to update user attributes with random values:
        ```php
        // ... (code to update firstname, lastname, and timezone)
        ```

## User Update Service

**(Include the User Update Service documentation from the previous response here)**

## Other details

*   **Branch:** The main feature is developed on the `feature/user-updates` branch.
*   **API Documentation:** The API documentation provided in the instructions is included in the `readme.md` file for reference.

## How to run

1.  Clone the repository: `git clone [your-repository-url]`
2.  Install dependencies: `composer install`
3.  Run the command to update user attributes: `php artisan update-user-attributes`
4.  Check the `storage/logs/userupdates.log` file for the output.

**Note:**  Remember to replace placeholders like `[your-repository-url]` and `[your-api-endpoint]` with your actual values.
