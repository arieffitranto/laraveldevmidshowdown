<?php

namespace App\Observers;

use App\Models\User;
use App\Services\UserUpdateService;

class UserObserver
{
    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        // Get the original attributes
        $original = $user->getOriginal();

        // Check for changes in relevant attributes (firstname, lastname, timezone)
        if ($original['firstname'] != $user->firstname ||
            $original['lastname'] != $user->lastname ||
            $original['timezone'] != $user->timezone) {

            // Use the UserUpdateService to update the user
            $userUpdateService = resolve(UserUpdateService::class);
            $userUpdateService->updateUsers([$user]);
        }
    }
}
