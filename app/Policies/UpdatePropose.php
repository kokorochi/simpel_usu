<?php

namespace App\Policies;

use App\FunctionClass\AuthRetrieval;
use App\User;
use App\Propose;
use Illuminate\Auth\Access\HandlesAuthorization;

class UpdatePropose extends AuthRetrieval {
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the propose.
     *
     * @param  \App\User $user
     * @param  \App\Propose $propose
     * @return mixed
     */
    public function view(User $user, Propose $propose)
    {
        if (! is_null($this->isSuper($user)) || ! is_null($this->isOperator($user)))
        {
            return true;
        }

        if (! is_null($this->isLecturer($user)))
        {
            $members = $propose->member()->where('external', null)->where('status', '<>', 'rejected')->get();
            if($user->nidn === $propose->created_by) return true; //Is Head

            foreach ($members as $member)
            {
                if($user->nidn === $member->nidn)
                {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Determine whether the user can create proposes.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the propose.
     *
     * @param  \App\User $user
     * @param  \App\Propose $propose
     * @return mixed
     */
    public function update(User $user, Propose $propose)
    {
        //
    }

    /**
     * Determine whether the user can delete the propose.
     *
     * @param  \App\User $user
     * @param  \App\Propose $propose
     * @return mixed
     */
    public function delete(User $user, Propose $propose)
    {
        //
    }
}
