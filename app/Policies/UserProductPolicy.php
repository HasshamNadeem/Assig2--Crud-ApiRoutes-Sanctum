<?php

namespace App\Policies;

use App\Models\Product;
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
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $LoggedUser, User $UrlUser)
    {
        return $LoggedUser->id === $UrlUser->id;
    }

    // /**
    //  * Determine whether the user can view any models.
    //  *
    //  * @return mixed
    //  */
    // public function viewAny(User $user) // Notice the '?' before ``User $user``
    // {
    //     dd('ahahah');

    //     return true;
    // }

    // public function attachDetails(User $loggedUser, Product $product)
    // {
    //     dd('Hi Hassham');

    //     // Implement your authorization logic here.
    //     // For example, check the user's role or permissions.

    //     // For demonstration purposes, I'm allowing any logged-in user to attach a product.
    //     return true;
    // }

    // public function create(User $user)
    // {
    //     return true;
    // }

    // public function delete(User $user, Product $product)
    // {
    //     dd('Hi');

    //     return true;
    // }
}
