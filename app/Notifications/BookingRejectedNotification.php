<?php

namespace App\Notifications;

use App\Models\Peminjaman;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BookingRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $peminjaman;
    public $alasan;

    public function __construct(Peminjaman $peminjaman, $alasan = null)
    {
        $this->peminjaman = $peminjaman;
        $this->alasan = $alasan ?? 'Tidak disebutkan';
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Booking Anda Ditolak')
            ->line('Maaf, booking buku Anda telah ditolak oleh admin.')
            ->line('Detail Booking:')
            ->line('Buku: ' . $this->peminjaman->buku->judul)
            ->line('Tanggal Pinjam: ' . $this->peminjaman->tanggal_pinjam->format('d M Y'))
            ->line('Alasan Penolakan: ' . $this->alasan)
            ->action('Lihat Buku Lain', route('member.katalog'))
            ->line('Silakan memilih buku lain yang tersedia.');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Booking untuk buku ' . $this->peminjaman->buku->judul . ' ditolak',
            'reason' => $this->alasan,
            'url' => route('member.katalog')
        ];
    }
}