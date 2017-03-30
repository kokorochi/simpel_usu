<?php

namespace App\Policies;

use App\FunctionClass\AuthRetrieval;
use App\User;
use App\Research;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResearchAuth extends AuthRetrieval {
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the research.
     *
     * @param  \App\User $user
     * @param  \App\Research $research
     * @return mixed
     */
    public function view(User $user, Research $research)
    {
        //
    }

    /**
     * Determine whether the user can create researches.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the research.
     *
     * @param  \App\User $user
     * @param  \App\Research $research
     * @return mixed
     */
    public function update(User $user, Research $research)
    {
        //
    }

    /**
     * Determine whether the user can delete the research.
     *
     * @param  \App\User $user
     * @param  \App\Research $research
     * @return mixed
     */
    public function delete(User $user, Research $research)
    {
        //
    }

    public function download(User $user, Research $research)
    {
        //Allow download for super user and operator
        if (! is_null($this->isSuper($user)) || ! is_null($this->isOperator($user)))
        {
            return true;
        }

        //Assigned reviewer can download propose too
        if (! is_null($this->isReviewer($user)))
        {
            $propose = $research->propose()->first();
            $propose_reviewers = $propose->researchReviewer()->get();
            foreach ($propose_reviewers as $propose_reviewer)
            {
                if ($propose_reviewer->nidn == $user->nidn)
                {
                    return true;
                }
            }
        }

        //Only head or member can download propose for lecturer
        if (! is_null($this->isLecturer($user)))
        {
            $members = $propose->member()->where('external', null)->where('status', '<>', 'rejected')->get();
            if ($user->nidn === $propose->created_by) return true; //Is Head

            foreach ($members as $member)
            {
                if ($user->nidn === $member->nidn)
                {
                    return true;
                }
            }
        }

        return false;
    }
}
