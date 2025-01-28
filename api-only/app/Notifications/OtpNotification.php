<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OtpNotification extends Notification
{
    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Kode OTP Verifikasi Email')
            ->greeting('Halo!')
            ->line('Berikut adalah kode OTP untuk verifikasi email Anda.')
            ->line('Kode OTP: ' . $this->otp)
            ->line('Kode ini akan kedaluwarsa dalam 5 menit.')
            ->line('Jika Anda tidak meminta kode ini, abaikan email ini.')
            ->salutation('Terima kasih, ' . config('app.name'));
    }
}
