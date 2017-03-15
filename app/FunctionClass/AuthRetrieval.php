<?php

namespace App\FunctionClass;

use App\User;
use Carbon\Carbon;

class AuthRetrieval {
    public function isSuper(User $user)
    {
        $today_date = Carbon::now()->toDateString();
        return $user->auths()
            ->where('auth_object_ref_id', '1')
            ->where('begin_date', '<=', $today_date)
            ->where('end_date', '>=', $today_date)
            ->first();
    }

    public function isOperator(User $user)
    {
        $today_date = Carbon::now()->toDateString();
        return $user->auths()
            ->where('auth_object_ref_id', '2')
            ->where('begin_date', '<=', $today_date)
            ->where('end_date', '>=', $today_date)
            ->first();
    }

    public function isReviewer(User $user)
    {
        $today_date = Carbon::now()->toDateString();
        return $user->auths()
            ->where('auth_object_ref_id', '3')
            ->where('begin_date', '<=', $today_date)
            ->where('end_date', '>=', $today_date)
            ->first();
    }

    public function isLecturer(User $user)
    {
        $today_date = Carbon::now()->toDateString();
        return $user->auths()
            ->where('auth_object_ref_id', '4')
            ->where('begin_date', '<=', $today_date)
            ->where('end_date', '>=', $today_date)
            ->first();
    }
}