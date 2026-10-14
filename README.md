# 📚 Sistem Otomatisasi Penilaian Pembelajaran Literasi Berbasis AI

## Panduan Instalasi dan Konfigurasi Sistem

Repositori ini merupakan sistem **Otomatisasi Penilaian Pembelajaran Literasi** berbasis *Large Language Model* (LLM) yang dikembangkan untuk membantu proses pembelajaran literasi membaca pada siswa sekolah dasar.

Sistem ini menggunakan teknologi berikut:

- **Laravel** — Backend utama dan antarmuka aplikasi.
- **n8n** — Otomatisasi workflow dan penghubung antara aplikasi dengan layanan AI.
- **LLM** — Model bahasa untuk pembangkitan soal, penilaian esai, dan pembuatan feedback otomatis.
- **Ollama / LLM API** — Penyedia model bahasa yang terhubung melalui workflow n8n, sesuai konfigurasi server.
- **MySQL** — Database untuk menyimpan data aplikasi, materi, soal, dan hasil asesmen.

### Fitur Utama

- Manajemen materi literasi.
- Pembangkitan soal secara otomatis menggunakan AI.
- Penilaian soal pilihan ganda.
- Penilaian esai otomatis berbasis AI.
- Pemberian feedback otomatis terhadap jawaban siswa.
- Penyimpanan dan pengelolaan hasil asesmen.

---

## 📥 1. Clone Repository

Clone repositori proyek ke perangkat lokal:

```bash
git clone https://github.com/abiyasa05/projek_skripsi_penilaian_literasi.git
```

Masuk ke direktori proyek Laravel:

```bash
cd "Projek Tesis/Penilaian Literasi/iClOP-V2"
```

> Sesuaikan lokasi direktori jika struktur folder pada repositori berbeda.

---

## 🛠️ 2. Instalasi Dependensi Laravel

Pastikan perangkat telah memiliki PHP, Composer, dan MySQL atau MariaDB yang sesuai dengan kebutuhan proyek.

Instal dependensi Laravel:

```bash
composer install
```

Jika proyek menggunakan dependensi frontend berbasis Node.js dan npm, instal dependensinya menggunakan:

```bash
npm install
```

Untuk menjalankan proses build frontend jika diperlukan:

```bash
npm run build
```

---

## ⚙️ 3. Konfigurasi Environment Laravel

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Pada Windows, file tersebut juga dapat disalin secara manual dan diberi nama `.env`.

Edit file `.env` dan sesuaikan konfigurasi aplikasi, database, autentikasi, serta integrasi AI.

Contoh konfigurasi dasar:

```env
APP_NAME=iClOP
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=

LOG_CHANNEL=stack
LOG_LEVEL=debug

CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120
QUEUE_CONNECTION=database
FILESYSTEM_DISK=local
```

### Konfigurasi Integrasi AI melalui n8n

Laravel menggunakan webhook n8n yang telah dikonfigurasi pada server untuk menjalankan proses AI.

Tambahkan konfigurasi URL webhook sesuai dengan workflow dan endpoint yang digunakan pada server:

```env
N8N_WEBHOOK_URL=https://alamat-server-n8n/webhook/ai-gateway-rag
```

Ganti URL contoh tersebut dengan URL webhook yang sebenarnya. Nama variabel environment harus disesuaikan dengan variabel yang dibaca oleh kode Laravel.

**Catatan:**
- Pastikan URL webhook sesuai dengan workflow n8n yang aktif.
- Gunakan URL produksi jika workflow telah diaktifkan pada server.
- Jika workflow menggunakan autentikasi, sesuaikan konfigurasi autentikasi pada Laravel dan n8n.
- Jangan menyimpan token autentikasi atau kredensial sensitif langsung di repositori publik.

---

## 🗄️ 4. Import Database

Siapkan database MySQL atau MariaDB sesuai dengan konfigurasi pada file `.env`.

### Opsi A: Menggunakan phpMyAdmin

1. Buka phpMyAdmin.
2. Buat database sesuai nilai `DB_DATABASE` pada file `.env`.
3. Pilih database tersebut.
4. Buka tab **Import**.
5. Pilih file database berekstensi `.sql`.
6. Klik **Import** atau **Go**.

### Opsi B: Menggunakan Terminal

```bash
mysql -u username -p nama_database < file.sql
```

Ganti `username`, `nama_database`, dan `file.sql` sesuai konfigurasi dan lokasi file database.

---

## 🔑 5. Persiapan Aplikasi Laravel

Jalankan perintah berikut dari direktori utama Laravel.

### Generate Application Key

```bash
php artisan key:generate
```

### Membuat Storage Link

```bash
php artisan storage:link
```

### Membersihkan Cache Konfigurasi

```bash
php artisan config:clear
php artisan cache:clear
```

Jika aplikasi menggunakan konfigurasi yang telah di-cache, jalankan kembali proses cache setelah konfigurasi selesai diperbarui.

---

## 🤖 6. Konfigurasi Workflow n8n

Backend AI tidak lagi menggunakan FastAPI yang dijalankan secara lokal. Proses integrasi AI ditangani oleh workflow n8n yang berjalan pada server.

Secara umum, workflow n8n digunakan untuk menangani permintaan dari Laravel, meneruskan permintaan ke model bahasa, memproses hasilnya, dan mengembalikan respons kepada aplikasi.

### Langkah Konfigurasi

1. Pastikan layanan n8n pada server dapat diakses.
2. Buka antarmuka n8n menggunakan alamat server yang telah dikonfigurasi.
3. Pastikan workflow AI Gateway tersedia dan telah dikonfigurasi.
4. Periksa konfigurasi webhook yang digunakan oleh Laravel.
5. Pastikan koneksi ke model bahasa atau penyedia LLM telah dikonfigurasi pada workflow.
6. Aktifkan workflow agar webhook produksi dapat menerima permintaan.
7. Pastikan format request dan response sesuai dengan kebutuhan aplikasi Laravel.

### Proses AI yang Ditangani

Workflow n8n dapat digunakan untuk menangani proses berikut sesuai implementasi sistem:

- **Generate:** pembangkitan soal berdasarkan materi literasi.
- **Score:** penilaian jawaban esai siswa berdasarkan kriteria penilaian.
- **Feedback:** pembuatan umpan balik otomatis untuk jawaban siswa.

> Instalasi Python, pembuatan virtual environment, instalasi `requirements.txt`, dan menjalankan `uvicorn` tidak diperlukan pada perangkat lokal untuk arsitektur ini. Layanan AI dijalankan melalui workflow n8n pada server.

---

## ▶️ 7. Menjalankan Aplikasi

Setelah konfigurasi database, environment, dan integrasi AI selesai, jalankan aplikasi Laravel:

```bash
php artisan serve
```

Secara default, aplikasi dapat diakses melalui:

http://localhost:8000

Jika frontend memerlukan development server Vite, buka terminal terpisah dan jalankan:

```bash
npm run dev
```

---

## ✅ 8. Verifikasi Instalasi

Setelah aplikasi berjalan, lakukan pengujian berikut:

1. Buka aplikasi melalui browser.
2. Pastikan halaman utama dapat ditampilkan.
3. Pastikan proses login berjalan dengan baik.
4. Pastikan data materi literasi dapat ditampilkan.
5. Uji fitur pembangkitan soal menggunakan AI.
6. Uji proses asesmen pilihan ganda.
7. Uji penilaian jawaban esai menggunakan AI.
8. Pastikan feedback otomatis dapat ditampilkan.
9. Pastikan hasil asesmen tersimpan dengan benar di database.
10. Periksa riwayat eksekusi workflow n8n jika terjadi kegagalan pada proses AI.

---

## 🧠 9. Arsitektur Sistem

Arsitektur sistem setelah menggunakan n8n sebagai penghubung layanan AI:

```text
User (Browser)
      |
      v
Laravel (iClOP-V2)
      |
      | HTTP Request
      v
n8n AI Gateway (Server)
      |
      v
Workflow AI
      |
      v
LLM Provider / Ollama
      |
      v
Hasil Generate / Scoring / Feedback
      |
      v
n8n mengembalikan respons ke Laravel
      |
      v
Laravel memproses dan menampilkan hasil
      |
      v
MySQL Database
```

Diagram tersebut merupakan gambaran umum alur sistem. Implementasi aktual dapat berbeda bergantung pada konfigurasi workflow, model bahasa, dan proses penyimpanan data pada Laravel.

---

## 📌 10. Catatan Penting

- Pastikan versi PHP dan Composer sesuai dengan kebutuhan proyek Laravel.
- Pastikan MySQL atau MariaDB berjalan dan dapat diakses oleh Laravel.
- Pastikan file `.env` telah dikonfigurasi dengan benar.
- Pastikan layanan n8n pada server aktif dan dapat diakses dari lingkungan tempat Laravel berjalan.
- Pastikan URL webhook dan format data request/response sesuai dengan implementasi Laravel.
- Pastikan workflow n8n telah diaktifkan sebelum digunakan melalui webhook produksi.
- Pastikan layanan model bahasa yang digunakan oleh workflow n8n dapat diakses.
- Jangan menyimpan file `.env`, API key, token, atau kredensial server di repositori publik.

---

## 📄 Lisensi dan Pengembangan

Repositori ini dikembangkan sebagai bagian dari proyek penelitian dan pengembangan sistem pembelajaran literasi berbasis AI.

Untuk pengembangan lebih lanjut, sesuaikan konfigurasi aplikasi Laravel, workflow n8n, database, dan layanan model bahasa dengan kebutuhan lingkungan pengujian maupun produksi.
