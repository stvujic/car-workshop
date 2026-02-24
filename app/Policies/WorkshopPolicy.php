<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Workshop;

class WorkshopPolicy
{
    public function manage(User $user, Workshop $workshop): bool
    {
        return $workshop->owner_id === $user->id;
    }
}
