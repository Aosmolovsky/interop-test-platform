<?php declare(strict_types=1);

namespace App\Policies;

use App\Models\Component;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpecificationPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param string $ability
     * @return bool
     */
    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * @param  User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {

    }

    /**
     * @param  User  $user
     * @param  Component  $model
     * @return mixed
     */
    public function view(User $user, Component $model)
    {

    }

    /**
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {

    }

    /**
     * @param  User  $user
     * @param  Component  $model
     * @return mixed
     */
    public function update(User $user, Component $model)
    {

    }

    /**
     * @param  User  $user
     * @param  Component  $model
     * @return mixed
     */
    public function delete(User $user, Component $model)
    {

    }
}
