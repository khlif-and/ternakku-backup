<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Services\WhatsAppService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOtpWAListener
{
    use InteractsWithQueue;

    protected $whatsAppService;

    /**
     * Create the event listener.
     *
     * @param  \App\Services\WhatsAppService  $whatsAppService
     * @return void
     */
    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        // Mengambil user dan otp dari event
        $user = $event->user;
        $otpCode = $event->otp;

        // Membuat pesan OTP
        $message = "OTP Anda adalah: $otpCode";

        // Mengirim pesan WhatsApp menggunakan WhatsAppService
        $response = $this->whatsAppService->sendMessage($user->phone_number, $message);

        // Periksa hasil pengiriman pesan
        if ($response['status'] === 'success') {
            // Lakukan sesuatu jika berhasil, jika diperlukan
        } else {
            // Lakukan sesuatu jika gagal, seperti log error
            \Log::error('Gagal mengirim pesan WhatsApp: ' . $response['message']);
        }
    }
}
