<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Policies\UserProductPolicy;
use Orion\Concerns\DisableAuthorization;
use Orion\Http\Controllers\RelationController;

class UserProductController extends RelationController
{
    protected $model = User::class;

    protected $relation = 'products';

    use DisableAuthorization;

    // protected $policy = UserProductPolicy::class;
}
