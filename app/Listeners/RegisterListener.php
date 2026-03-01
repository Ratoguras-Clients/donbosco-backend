<?php

namespace App\Listeners;

use App\Models\LoginActivity;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class RegisterListener
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(Registered $event)
    {
        $userId = $event->user->id;
        // dd($event);
        session(['user_id' => $event->user->id]);

        $activity = LoginActivity::create([
            'user_id' => $userId,
            'type' => 'register',
            'ip_address' => $this->request->ip(),
            'user_agent' => $this->request->userAgent(),
            'logged_in_at' => now(),
        ]);
    }
}
