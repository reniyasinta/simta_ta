<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PengajuanDosenMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mahasiswa;
    public $judul;

    public function __construct($mahasiswa, $judul)
    {
        $this->mahasiswa = $mahasiswa;
        $this->judul = $judul;
    }

    public function build()
    {
        return $this->subject('Pengajuan Dosen Pembimbing TA')
            ->view('pages.emails.pengajuan_dosen');
    }
}
