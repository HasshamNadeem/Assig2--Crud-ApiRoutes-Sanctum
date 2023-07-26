<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserProductPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function store(User $LoggedUser, User $UrlUser)
    {
        return $LoggedUser->id === $UrlUser->id;
    }

    public function index(User $LoggedUser, User $UrlUser)
    {
        return $LoggedUser->id === $UrlUser->id;
    }
}
