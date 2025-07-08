<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMemberNotification extends Notification
{
    use Queueable;

    public $password;

    public function __construct($password)
    {
        $this->password = $password;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Akun Anda Telah Dibuat')
            ->line('Akun Anda telah dibuat oleh administrator.')
            ->line('Berikut adalah password sementara Anda:')
            ->line($this->password)
            ->line('Silakan login dan verifikasi email Anda, kemudian ubah password Anda.')
            ->action('Login', url('/login'));
    }
}