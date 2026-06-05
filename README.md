# 📚 Sistem Otomatisasi Penilaian Pembelajaran Literasi Berbasis AI

## Panduan Instalasi & Konfigurasi Sistem

Repositori ini merupakan sistem **Otomatisasi Penilaian Pembelajaran Literasi** berbasis:

* **Laravel (Frontend & Backend utama)**
* **FastAPI (Backend AI Service)**
* **LLM Lokal (LLaMA/Mistral/Ollama)**
* **MySQL Database**

Sistem ini digunakan untuk:

* Manajemen materi literasi
* Generate soal otomatis
* Penilaian pilihan ganda
* **Penilaian esai otomatis berbasis AI**
* Feedback otomatis

---

# 📥 Clone Repository Proyek

Salin repository proyek ke perangkat lokal:

```bash
git clone https://github.com/abiyasa05/projek_skripsi_penilaian_literasi.git
```

---

# 📁 Masuk ke Direktori Proyek & Instal Dependensi Laravel

```bash
cd "Projek Tesis/Penilaian Literasi/iClOP-V2"
composer install
```

---

# 🤖 Setup Backend AI (FastAPI + LLM)

Buka terminal baru:

```bash
cd Projek Tesis/Model LLM/fastapi-llama
```

## Buat dan Aktifkan Virtual Environment

```bash
python -m venv venv

# Linux / MacOS
source venv/bin/activate

# Windows (CMD)
venv\Scripts\activate
```

## Instal Dependensi Python

```bash
pip install -r requirements.txt
```

## Jalankan Backend AI

```bash
uvicorn main:app --host 127.0.0.1 --port 8010 --reload
```

FastAPI AI Service akan berjalan di:
👉 [http://127.0.0.1:8010](http://127.0.0.1:8010)

---

# ⚙️ Konfigurasi File Environment (.env)

Masuk ke direktori utama Laravel:

```bash
cd "Projek Tesis/Penilaian Literasi/iClOP-V2"
```

Salin file contoh:

```bash
cp .env.example .env
```

---

# 📝 Konfigurasi File .env

Edit file `.env` dan sesuaikan:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

GOOGLE_CLIENT_ID=1067406058813-1tfpvhcunbfve9ftpse3644fv0f74maq.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=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
GOOGLE_REDIRECT_URI=https://mediumslateblue-kingfisher-461508.hostingersite.com/google/redirect
```

---

# 🗄️ Import Database

## Opsi 1: phpMyAdmin

1. Buka phpMyAdmin
2. Pilih database sesuai `.env`
3. Klik tab **Import**
4. Pilih file `.sql`
5. Klik **Go**

---

## Opsi 2: Terminal (CLI)

```bash
mysql -u username -p nama_database < file.sql
```

Ganti:

* `username`
* `nama_database`
* `file.sql`

---

# ▶️ Menjalankan Proyek

## Generate Key Laravel

```bash
php artisan key:generate
```

## Storage Link

```bash
php artisan storage:link
```

## Jalankan Server Laravel

```bash
php artisan serve
```

Aplikasi berjalan di:
👉 [http://localhost:8000](http://localhost:8000)

---

# ✅ Verifikasi Instalasi

1. Buka browser
2. Akses: [http://localhost:8000](http://localhost:8000)
3. Pastikan:

   * Halaman utama tampil
   * Login berjalan
   * Backend AI FastAPI aktif
   * Generate soal berjalan
   * Penilaian esai otomatis berjalan

---

# 🧠 Arsitektur Sistem

```text
User (Browser)
   ↓
Laravel (iClOP-V2)
   ↓ API
FastAPI (LLM Service)
   ↓
LLM Lokal (LLaMA/Mistral/Ollama)
   ↓
Database MySQL
```

---

# 📌 Catatan Penting

* Pastikan Python ≥ 3.9
* Pastikan MySQL aktif
* Pastikan port 8010 tidak digunakan aplikasi lain
* Pastikan FastAPI aktif sebelum Laravel mengakses AI
