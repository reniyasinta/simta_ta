

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pengajuan Dosen Pembimbing</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">
    <p>Yth. Bapak/Ibu Dosen,</p>

    <p>Seorang mahasiswa telah mengajukan Bapak/Ibu sebagai dosen pembimbing 1 dalam sistem SIMTA. Berikut detail pengajuannya:</p>

    <ul>
        <li><strong>Nama:</strong> {{ $mahasiswa->nama_mhs }}</li>
        <li><strong>NIM:</strong> {{ $mahasiswa->user->nim }}</li>
        <li><strong>Judul TA:</strong> {{ $judul }}</li>
    </ul>

    <p>Silakan login ke sistem SIMTA untuk memproses pengajuan tersebut melalui link berikut:</p>

    <p>
        <a href="{{ url('/login') }}" target="_blank" style="background-color: #1a73e8; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;">
            Login ke SIMTA
        </a>
    </p>

    <p>Terima kasih atas perhatian dan kerjasamanya.</p>

    <p>Hormat kami,<br>
    Tim Pengelola SIMTA</p>
</body>
</html>
