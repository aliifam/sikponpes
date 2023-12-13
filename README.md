# SIK Ponpes

Sistem informasi keuangan pondok pesantren, aplikasi akunting simpel untuk pondok pesantren.

## Fitur

-   Multi Pesantren
-   Manajemen Akun
-   Buku Besar
-   Buku Pembantu Aktiva Tetap
-   Buku Pembantu Piutang
-   Buku Pembantu Utang
-   Jurnal Umum
-   Neraca Lajur
-   Neraca Awal
-   Laporan Aktivitas
-   Laporan Arus Kas
-   Laporan Posisi Keuangan
-   Laporan Perubahan Ekuitas
-   Export ke Excel dan PDF

## Instalasi

1. Clone repository ini
2. Jalankan `composer install`
3. Copy file `.env.example` menjadi `.env`
4. Jalankan `php artisan key:generate`
5. Buat database baru
6. Isi konfigurasi database di file `.env`
7. Jalankan `php artisan migrate`
8. jalankan `npm install`
9. jalankan `npm run dev`
10. Jalankan `php artisan serve`
11. Isi konfigurasi smtp email di file `.env`

## Tech Stack

-   Laravel 10
-   Tailwind CSS
-   Alpine JS
-   Livewire
-   FilamentPHP
