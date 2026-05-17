# UI_QA_CHECKLIST.md - NATAKOS

Checklist ini dipakai untuk pengecekan ulang tampilan dan flow utama NATAKOS setelah ada perubahan UI, layout, atau fitur.

## Persiapan

- [ ] Jalankan aplikasi dan pastikan halaman publik, admin, dan tenant bisa diakses.
- [ ] Pastikan database yang dipakai adalah `natakos`.
- [ ] Pastikan ada minimal:
  - [ ] 1 akun admin aktif
  - [ ] 1 akun tenant aktif
  - [ ] 1 kamar tersedia
  - [ ] 1 kamar terisi
  - [ ] 1 data penghuni aktif
  - [ ] 1 data pembayaran
- [ ] Jika ingin menguji preview gambar publik/admin, pastikan `php artisan storage:link` sudah dijalankan.

## Auth Dan Role

- [ ] Buka `/login`.
- [ ] Tampilan login rapi di desktop.
- [ ] Tampilan login rapi di mobile.
- [ ] Login admin berhasil.
- [ ] Admin diarahkan ke `/admin/dashboard`.
- [ ] Login tenant berhasil.
- [ ] Tenant diarahkan ke `/tenant/dashboard`.
- [ ] Guest yang membuka `/admin/dashboard` diarahkan ke `/login`.
- [ ] Guest yang membuka `/tenant/dashboard` diarahkan ke `/login`.
- [ ] Admin yang membuka area tenant dipantulkan ke dashboard admin.
- [ ] Tenant yang membuka area admin dipantulkan ke dashboard tenant.

## Layout Admin

- [ ] Navbar admin tampil konsisten di semua halaman admin.
- [ ] Menu aktif admin terlihat jelas.
- [ ] Header halaman admin punya eyebrow, title, dan description yang konsisten.
- [ ] Tombol aksi utama admin tampil dengan gaya yang sama.
- [ ] Flash success/error admin tampil jelas dan tidak menabrak layout.
- [ ] Empty state admin konsisten antar halaman.

## Dashboard Admin

- [ ] `/admin/dashboard` memakai layout admin yang benar.
- [ ] Card statistik tampil rapi dan sejajar.
- [ ] Badge warning pembayaran tampil sesuai status.
- [ ] Badge warning masa tinggal tampil sesuai status.
- [ ] Tabel ringkas pembayaran mendekati tenggat tampil rapi.
- [ ] Tabel ringkas pembayaran terlambat tampil rapi.
- [ ] Tabel ringkas masa tinggal tampil rapi.
- [ ] Empty state dashboard tetap layak jika data warning kosong.

## CRUD Kamar Admin

- [ ] `/admin/rooms` tampil rapi di desktop.
- [ ] `/admin/rooms` tetap terbaca di mobile.
- [ ] Tombol tambah kamar jelas terlihat.
- [ ] Tabel kamar menampilkan badge status dengan konsisten.
- [ ] Empty state kamar tampil rapi saat data kosong.
- [ ] Form tambah kamar rapi dan terstruktur.
- [ ] Form edit kamar rapi dan terstruktur.
- [ ] Preview `main_image` tampil dengan baik jika ada.
- [ ] Checkbox fasilitas tampil rapi dan terkelompok.

## CRUD Fasilitas Admin

- [ ] `/admin/facilities` tampil rapi di desktop.
- [ ] `/admin/facilities` tetap terbaca di mobile.
- [ ] Badge `room` dan `public` tampil konsisten.
- [ ] Empty state fasilitas tampil rapi saat data kosong.
- [ ] Form tambah/edit fasilitas rapi dan mudah dipindai.

## CRUD Penghuni Admin

- [ ] `/admin/tenants` tampil rapi di desktop.
- [ ] `/admin/tenants` tetap terbaca di mobile.
- [ ] Badge status penghuni tampil konsisten.
- [ ] Empty state penghuni tampil rapi saat data kosong.
- [ ] Form tambah/edit penghuni terbagi jelas per section.
- [ ] Form tetap jelas saat error validasi muncul.

## CRUD Pembayaran Admin

- [ ] `/admin/payments` tampil rapi di desktop.
- [ ] `/admin/payments` tetap terbaca di mobile.
- [ ] Badge status pembayaran tampil konsisten.
- [ ] Warning deadline tampil jelas dan tidak membingungkan.
- [ ] Empty state pembayaran tampil rapi saat data kosong.
- [ ] Form tambah/edit pembayaran terbagi jelas per section.
- [ ] Preview `proof_image` tampil baik jika ada.
- [ ] Akses bukti pembayaran hanya melalui route admin yang benar.

## Layout Tenant

- [ ] Navbar tenant konsisten.
- [ ] Active state tenant terlihat jelas.
- [ ] Header dashboard tenant punya hierarchy yang jelas.
- [ ] CTA WhatsApp tenant terlihat jelas.

## Dashboard Tenant

- [ ] `/tenant/dashboard` hanya menampilkan data milik tenant login.
- [ ] Card informasi kamar tampil rapi.
- [ ] Card masa tinggal tampil rapi.
- [ ] Card pembayaran tampil rapi.
- [ ] Alert warning pembayaran tampil sesuai kondisi.
- [ ] Alert warning masa tinggal tampil sesuai kondisi.
- [ ] Empty state tenant tanpa data aktif tampil benar.
- [ ] Empty state tenant tanpa pembayaran tampil benar.

## Layout Publik

- [ ] Navbar publik tampil konsisten.
- [ ] Active state menu Home/Kamar terlihat jelas.
- [ ] Tombol Login terlihat jelas.
- [ ] Footer publik konsisten di semua halaman publik.

## Homepage Publik

- [ ] `/` tampil baik di desktop.
- [ ] `/` tampil baik di mobile.
- [ ] Hero section terlihat kuat dan rapi.
- [ ] Ringkasan cepat tampil rapi.
- [ ] Card kamar pilihan tampil konsisten.
- [ ] Section fasilitas tampil rapi.
- [ ] Section kontak WhatsApp tampil jelas.
- [ ] Empty state homepage tetap layak jika kamar tersedia kosong.

## Daftar Kamar Publik

- [ ] `/rooms` tampil baik di desktop.
- [ ] `/rooms` tampil baik di mobile.
- [ ] Card kamar konsisten satu sama lain.
- [ ] Badge status kamar terbaca jelas.
- [ ] Harga, ukuran, lantai, dan CTA tertata rapi.
- [ ] Empty state kamar tampil baik jika data kosong.

## Detail Kamar Publik

- [ ] `/rooms/{slug}` tampil baik di desktop.
- [ ] `/rooms/{slug}` tampil baik di mobile.
- [ ] Foto utama tampil proporsional.
- [ ] Informasi kamar mudah dipindai.
- [ ] Section fasilitas kamar dan fasilitas umum jelas.
- [ ] Galeri tambahan tampil baik jika ada.
- [ ] Contact band WhatsApp tampil jelas.

## Badge Dan Alert

- [ ] Badge status kamar konsisten di admin, tenant, dan publik.
- [ ] Badge status tenant konsisten.
- [ ] Badge status pembayaran konsisten.
- [ ] Badge warning tenggat pembayaran konsisten.
- [ ] Badge warning masa tinggal konsisten.
- [ ] Alert warning kuning, kuning tegas, dan merah terbaca jelas.

## Empty State Dan Flash Message

- [ ] Empty state punya judul yang jelas.
- [ ] Empty state punya deskripsi yang membantu.
- [ ] Empty state punya CTA yang relevan.
- [ ] Flash success tidak terlalu mencolok tapi tetap jelas.
- [ ] Flash error terlihat jelas dan mudah dibaca.

## Format Data

- [ ] Semua harga tampil dalam format rupiah yang konsisten.
- [ ] Semua tanggal tampil dalam format Indonesia yang konsisten.
- [ ] Metadata kecil memakai style teks sekunder yang konsisten.

## Responsive Mobile

- [ ] Navbar admin tidak pecah buruk di mobile.
- [ ] Navbar tenant tidak pecah buruk di mobile.
- [ ] Navbar publik tidak pecah buruk di mobile.
- [ ] Tabel admin tetap terbaca di mobile.
- [ ] Form admin tetap nyaman di mobile.
- [ ] Card dashboard admin tetap rapi di mobile.
- [ ] Card dashboard tenant tetap rapi di mobile.
- [ ] Hero publik tetap rapi di mobile.
- [ ] Card kamar publik tetap rapi di mobile.

## Upload Gambar

- [ ] Upload `main_image` kamar berhasil.
- [ ] Preview `main_image` tampil benar.
- [ ] Upload `proof_image` pembayaran berhasil.
- [ ] Preview `proof_image` tampil benar.
- [ ] File gambar tidak merusak layout.

## WhatsApp

- [ ] Tombol WhatsApp di dashboard tenant benar.
- [ ] Tombol WhatsApp di homepage publik benar.
- [ ] Tombol WhatsApp di detail kamar publik benar.
- [ ] Nomor WhatsApp memakai format `62`, bukan `0`.
- [ ] Pesan default publik benar.
- [ ] Pesan detail kamar benar.

## Regression Check

- [ ] Tidak ada halaman yang kehilangan active state menu.
- [ ] Tidak ada badge yang berubah arti warna secara tidak sengaja.
- [ ] Tidak ada tombol utama yang berubah style sendiri.
- [ ] Tidak ada form yang spacing-nya rusak setelah polish.
- [ ] Tidak ada route utama yang menghasilkan error 500.

## Aksesibilitas Dasar

- [ ] Semua tombol bisa difokuskan dengan keyboard.
- [ ] Focus state terlihat jelas saat menekan Tab.
- [ ] Input form punya label yang jelas.
- [ ] Error validasi mudah dibaca.
- [ ] Warna badge/alert tetap terbaca untuk pengguna yang sulit membedakan warna.
- [ ] Link dan tombol punya teks yang jelas, bukan hanya ikon.

## Validasi Form Dan Error State

- [ ] Form tetap rapi saat ada banyak error validasi.
- [ ] Input yang error punya pesan error tepat di bawah field.
- [ ] Data yang sudah diisi tidak hilang saat validasi gagal.
- [ ] Tombol submit tidak membingungkan saat form gagal dikirim.
- [ ] Form upload menolak file non-gambar.
- [ ] Form upload menolak file gambar terlalu besar.

## Delete Confirmation

- [ ] Aksi hapus kamar meminta konfirmasi.
- [ ] Aksi hapus fasilitas meminta konfirmasi.
- [ ] Aksi hapus penghuni meminta konfirmasi.
- [ ] Aksi hapus pembayaran meminta konfirmasi.
- [ ] Pesan konfirmasi hapus jelas menyebut data yang akan dihapus.
- [ ] Jika hapus diblok karena data masih dipakai, pesan error tampil jelas.

## Data Banyak / Pagination

- [ ] Tabel kamar tetap nyaman jika data banyak.
- [ ] Tabel fasilitas tetap nyaman jika data banyak.
- [ ] Tabel penghuni tetap nyaman jika data banyak.
- [ ] Tabel pembayaran tetap nyaman jika data banyak.
- [ ] Pagination, jika ada, tampil rapi di desktop.
- [ ] Pagination, jika ada, tampil rapi di mobile.

## Halaman Error

- [ ] Route yang tidak ada menampilkan halaman 404 yang layak.
- [ ] Akses tidak valid tidak menghasilkan tampilan error mentah.
- [ ] Error 403, jika ada, tampil dengan pesan yang mudah dipahami.
- [ ] Error 500 tidak muncul pada flow utama aplikasi.

## Loading Dan State Tombol

- [ ] Tombol submit tidak terlihat rusak saat diklik.
- [ ] Tidak ada tombol aksi yang terlalu kecil di mobile.
- [ ] Tombol utama dan tombol sekunder konsisten.
- [ ] Tombol danger/hapus terlihat berbeda dari tombol biasa.
- [ ] Link WhatsApp terlihat sebagai CTA utama ketika dibutuhkan.

## Gambar Dan Media

- [ ] Gambar kamar punya fallback jika file tidak ditemukan.
- [ ] Logo kos punya fallback jika belum diupload.
- [ ] Gambar tidak gepeng atau terpotong buruk.
- [ ] Gambar besar tidak membuat halaman terlalu lambat.
- [ ] Gambar memakai ukuran proporsional di mobile.

## Data Tidak Konsisten

- [ ] Payment tetap aman jika tenant terhapus atau relasi tenant null.
- [ ] Tenant tetap aman jika room null.
- [ ] Room tetap aman jika tidak punya fasilitas.
- [ ] Dashboard admin tetap aman jika tidak ada pembayaran.
- [ ] Dashboard tenant tetap aman jika tidak ada tagihan.
- [ ] Halaman publik tetap aman jika tidak ada kos_profiles.

## Browser Check

- [ ] Tampilan dicek di Chrome/Chromium.
- [ ] Tampilan dicek di Firefox.
- [ ] Tampilan mobile dicek lewat responsive mode browser.
- [ ] Tidak ada horizontal overflow yang tidak perlu di halaman publik.
- [ ] Tidak ada teks yang kepotong di card penting.

## Deployment Readiness UI

- [ ] Tidak ada teks dummy seperti lorem ipsum.
- [ ] Tidak ada link mati di navbar.
- [ ] Tidak ada tombol yang belum berfungsi.
- [ ] Tidak ada route debug yang tampil di UI.
- [ ] Semua halaman utama punya title yang jelas.

## Catatan QA

Tanggal pengecekan:

```text
...
```

Nama pengecek:

```text
...
```

Temuan:

```text
...
```
