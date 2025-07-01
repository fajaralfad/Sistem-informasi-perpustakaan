<?php

// app/Notifications/BookingConfirmed.php
namespace App\Notifications;

use App\Models\Peminjaman;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BookingConfirmed extends Notification implements ShouldQueue
{
    use Queueable;

    public $peminjaman;

    public function __construct(Peminjaman $peminjaman)
    {
        $this->peminjaman = $peminjaman;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Booking Buku Dikonfirmasi')
            ->line('Booking buku Anda telah dikonfirmasi oleh admin perpustakaan.')
            ->line('Detail Booking:')
            ->line('- Judul Buku: ' . $this->peminjaman->buku->judul)
            ->line('- Tanggal Pinjam: ' . $this->peminjaman->tanggal_pinjam->format('d/m/Y'))
            ->line('- Tanggal Kembali: ' . $this->peminjaman->tanggal_kembali->format('d/m/Y'))
            ->action('Lihat Detail', url('/riwayat-peminjaman'))
            ->line('Terima kasih telah menggunakan layanan perpustakaan kami.');
    }
}
