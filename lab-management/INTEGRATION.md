**Integrasi Template Sneat — Ringkasan Perubahan**

- **Tujuan**: Mengintegrasikan template HTML/CSS/JS (Sneat) ke dalam proyek Laravel agar tampilan dasar tersedia untuk dikembangkan menjadi dashboard peran.

- **File Baru**:
  - [resources/views/layouts/sneat.blade.php](resources/views/layouts/sneat.blade.php#L1-L200) : Layout Blade yang memuat stylesheet dan script dari `public/sneat`.
  - [resources/views/sneat/index.blade.php](resources/views/sneat/index.blade.php#L1-L200) : Contoh halaman beranda yang extend layout Sneat.
  - [public/sneat/README.txt](public/sneat/README.txt#L1-L200) : Petunjuk untuk meletakkan assets Sneat.
  - [scripts/copy_sneat_assets.bat](scripts/copy_sneat_assets.bat#L1-L200) : Windows batch helper untuk menyalin template (sesuaikan sumber).
  - [scripts/copy_sneat_assets.sh](scripts/copy_sneat_assets.sh#L1-L200) : Shell helper untuk menyalin template (sesuaikan sumber).
  - [INTEGRATION.md](INTEGRATION.md#L1-L200) : Dokumen ini (ringkasan perubahan dan langkah selanjutnya).

- **Perubahan yang dilakukan**:
  - Root route di [routes/web.php](routes/web.php#L1-L40) diubah agar mengembalikan view `sneat.index`.

- **Langkah selanjutnya (yang perlu Anda lakukan untuk menyelesaikan integrasi)**
  1. Salin seluruh isi folder template HTML (css, js, img, vendor) yang Anda lampirkan ke `public/sneat` (bisa gunakan skrip di `scripts/`).
  2. Pastikan file utama CSS/JS cocok dengan yang dipanggil di `layouts/sneat.blade.php` (default: `sneat/assets/css/style.css` dan `sneat/assets/js/main.js`). Jika nama berbeda, edit path di layout.
  3. Konversi halaman HTML yang Anda butuhkan menjadi Blade views di `resources/views/sneat/` dan ganti konten statis menjadi komponen/partial jika perlu.
  4. Integrasikan komponen dashboard per peran (lihat route `/dashboard` logic) dengan membuat view seperti `dashboard.admin`, `dashboard.kalab`, dll. atau mengarahkan ke yang sudah ada.
  5. Jalankan `npm run dev` atau `vite` jika perlu untuk membangun asset frontend (jika Anda menggabungkan asset dengan build tool).

- **Catatan**: Saya menambahkan layout dan contoh view agar Anda dapat melihat template di aplikasi sekarang (kunjungi `/`). Namun, saya tidak memindahkan seluruh file template otomatis — saya menyediakan skrip dan instruksi agar Anda bisa memindahkannya dari path template yang Anda lampirkan.
