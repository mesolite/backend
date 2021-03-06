<?php

namespace App\Models;

use Amethyst\Models\User as BaseUser;
use Amethyst\Traits\PermissionTrait;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Railken\Lem\Contracts\AgentContract;

class User extends BaseUser implements AuthenticatableContract, AuthorizableContract, AgentContract
{
    use PermissionTrait;
    use Authenticatable;
    use HasApiTokens;
    use Notifiable;

    // tmp lag
    public function can($p, $a = [])
    {
        return true;
    }

    /**
     * Retrieve user for passport oauth.
     *
     * @param string $identifier
     *
     * @return object|null
     */
    public function findForPassport($identifier)
    {
        return (new static())->newQuery()->orWhere(function ($q) use ($identifier) {
            return $q->orWhere('email', $identifier)->orWhere('name', $identifier);
        })->where('enabled', 1)->first();
    }
}
