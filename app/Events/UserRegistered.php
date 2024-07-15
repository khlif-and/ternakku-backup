<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class UserRegistered
{
    use SerializesModels;

    public $user;
    public $otp;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $otp)
    {
        $this->user = $user;
        $this->otp = $otp;
    }
}
