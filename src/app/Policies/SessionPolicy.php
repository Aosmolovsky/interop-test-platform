<?php declare(strict_types=1);

namespace App\Policies;

use App\Models\Session;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SessionPolicy
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
     * @param  Session  $model
     * @return mixed
     */
    public function view(User $user, Session $model)
    {
        return $model->owner->is($user);
    }

    /**
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * @param  User  $user
     * @param  Session  $model
     * @return mixed
     */
    public function update(User $user, Session $model)
    {
        return $model->owner->is($user);
    }

    /**
     * @param  User  $user
     * @param  Session  $model
     * @return mixed
     */
    public function delete(User $user, Session $model)
    {
        return $model->owner->is($user);
    }

    /**
     * @param  User  $user
     * @param  Session  $model
     * @return mixed
     */
    public function restore(User $user, Session $model)
    {
        return $model->owner->is($user);
    }
}