Kebijakan Keamanan

Keamanan adalah prioritas utama bagi Aplikasi Sistem Booking Klinik. Kami berkomitmen untuk melindungi data pengguna dan menjaga integritas sistem kami. Dokumen ini menguraikan kebijakan keamanan dan prosedur untuk melaporkan kerentanan.
Pelaporan Kerentanan

Kami sangat menghargai upaya para peneliti keamanan dan komunitas untuk membantu kami menjaga keamanan aplikasi ini. Jika Anda menemukan kerentanan keamanan, mohon laporkan kepada kami sesegera mungkin.

Bagaimana Melaporkan Kerentanan:

    Laporkan Secara Rahasia: Mohon laporkan kerentanan secara langsung kepada tim kami melalui email di:
    contact@enthistechproject.my.id (Ganti dengan email keamanan yang relevan)

    Sertakan Detail: Dalam laporan Anda, sertakan informasi berikut:

        Deskripsi singkat mengenai kerentanan.

        Langkah-langkah reproduksi yang jelas (misalnya, URL, parameter, akun yang digunakan).

        Dampak potensial dari kerentanan.

        Versi aplikasi yang terpengaruh (jika diketahui).

        Bukti konsep (misalnya, screenshot, rekaman video singkat, atau payload yang relevan) sangat membantu, tetapi pastikan tidak mengekspos data pengguna yang sebenarnya.

    Jangan Publikasikan: Mohon jangan mempublikasikan detail kerentanan sampai kami memiliki kesempatan untuk menyelidiki dan memperbaikinya. Kami berkomitmen untuk bekerja sama dengan Anda secara transparan dan tepat waktu.

Tanggapan Kami

Setelah menerima laporan kerentanan, tim keamanan kami akan:

    Konfirmasi Penerimaan: Mengirimkan konfirmasi penerimaan laporan Anda dalam waktu 24-48 jam kerja.

    Investigasi: Melakukan investigasi menyeluruh terhadap kerentanan yang dilaporkan.

    Pembaruan Status: Memberikan pembaruan status secara berkala kepada Anda mengenai kemajuan investigasi dan perbaikan.

    Pengakuan: Jika kerentanan dikonfirmasi dan diperbaiki, kami akan mengakui kontribusi Anda (jika Anda setuju) dalam catatan rilis atau halaman pengakuan kami.

Praktik Keamanan

Kami menerapkan praktik keamanan berikut dalam pengembangan dan pengelolaan aplikasi ini:

    Prinsip Least Privilege: Pengguna dan sistem hanya diberikan hak akses minimum yang diperlukan untuk menjalankan fungsinya.

    Validasi Input: Semua input pengguna divalidasi secara ketat untuk mencegah serangan seperti SQL Injection dan Cross-Site Scripting (XSS).

    Otentikasi Kuat: Penggunaan kata sandi yang di-hash (bcrypt), otentikasi multi-faktor (jika diimplementasikan), dan manajemen sesi yang aman.

    Otorisasi Berbasis Peran: Kontrol akses granular menggunakan Spatie Laravel Permission untuk memastikan pengguna hanya dapat mengakses resource yang diizinkan.

    Enkripsi Data Sensitif: Data sensitif, seperti kunci API pembayaran, dienkripsi saat disimpan dalam basis data.

    Pembaruan Dependensi: Dependensi dan package pihak ketiga diperbarui secara berkala untuk mengatasi kerentanan yang diketahui.

    Pencatatan (Logging): Aktivitas penting dicatat untuk tujuan audit dan deteksi anomali.

Terima kasih atas kerja sama Anda dalam menjaga keamanan Aplikasi Sistem Booking Klinik.