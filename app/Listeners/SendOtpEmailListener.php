<?php

namespace App\Listeners;

use App\Models\Otp;
use App\Mail\OtpMail;
use App\Events\UserRegistered;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOtpEmailListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  \App\Events\UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        // Kirim email OTP
        Mail::to($event->user->email)->send(new OtpMail($event->otp));
    }
}
