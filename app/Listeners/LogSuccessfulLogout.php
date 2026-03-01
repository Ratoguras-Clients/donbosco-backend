<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Models\LoginActivity;
use Illuminate\Support\Facades\DB;

class LogSuccessfulLogout
{
    public function handle(Logout $event)
    {
        LoginActivity::where('user_id', $event->user->id)
            ->whereNull('logged_out_at')
            ->latest()
            ->first()?->update([
                'logged_out_at' => now(),
            ]);

            DB::table('sessions')
            ->where('user_id', $event->user->id)
            ->where('id', '!=', session()->getId()) // Exclude the current session
            ->delete();

            // dd(session()->getId());
            // dd($event->user->id);
            // dd('Logout event triggered');

    }
}
