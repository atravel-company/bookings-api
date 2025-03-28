<?php

namespace App\Observers;

use App\User;
use Illuminate\Support\Str;

class UserObserver
{
    public function created(User $user)
    {
        if (Str::endsWith($user->email, ['@gmail.com', '@yahoo.com'])) {
            $creator = auth()->user();

            \Log::channel('monitored_users')->info('User created using a suspicious email address', [
                'user_id'    => $user->id,
                'email'      => $user->email,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'creator_id'   => $creator ? $creator->id : null,
                'creator_email'=> $creator ? $creator->email : null,
            ]);
        }
    }
}
