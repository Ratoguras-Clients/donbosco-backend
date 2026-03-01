<?php
namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use App\Models\LoginActivity;

class LogSuccessfulLogin
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(Login $event)
    {
        $userId = $event->user->id;

        session(['user_id' => $event->user->id]);

        // // Close any previously open sessions
        // LoginActivity::where('user_id', $userId)
        //     ->whereNull('logged_out_at')
        //     ->update(['logged_out_at' => now()]);

        // Log the new login activity
        $activity = LoginActivity::create([
            'user_id' => $userId,
            'type' => 'login',
            'ip_address' => $this->request->ip(),
            'user_agent' => $this->request->userAgent(),
            'logged_in_at' => now(),
        ]);

    }
}
