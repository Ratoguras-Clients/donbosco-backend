<?php

namespace App\Listeners;

use App\Models\LoginActivity;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;

class ResetPasswordListener
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(PasswordReset $event)
    {
        $userId = $event->user->id;

        session(['user_id' => $event->user->id]);

        $activity = LoginActivity::create([
            'user_id' => $userId,
            'type' => 'reset_password',
            'ip_address' => $this->request->ip(),
            'user_agent' => $this->request->userAgent(),
            'logged_in_at' => now(),
        ]);
    }
}
