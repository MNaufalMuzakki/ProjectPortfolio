# Portfolio Project — Technical Test Fullstack Intern

Personal portfolio landing page dengan CRUD konten di halaman yang sama (View Mode & Edit Mode), dibangun dengan **Laravel + Livewire + Tailwind CSS**.

## Fitur yang Sudah Dibuat

- **Satu halaman, dua mode:** View (landing publik) dan Edit (CRUD inline), tanpa dashboard terpisah
- **Data Diri (Profile):** update nama, role, bio, kontak, avatar
- **CRUD Keahlian (Skills)**
- **CRUD Pengalaman (Experiences)**
- **CRUD Pendidikan & Sertifikasi (Educations)**
- **CRUD Proyek (Projects)** — termasuk tech stack & thumbnail opsional
- Konten tersimpan di database; perubahan langsung terlihat di landing page
- Validasi server-side (Livewire)
- Migration + seeder data contoh
- UI responsif (desktop & mobile)

## Tech Stack & Metode

| Teknologi / Metode | Dipakai di mana |
|--------------------|-----------------|
| **Laravel 13** | Backend utama (`composer.json`) |
| **Livewire 4** | Semua CRUD + toggle View/Edit di `resources/views/components/⚡portfolio-page.blade.php` |
| **Blade** | Layout di `resources/views/welcome.blade.php` |
| **Tailwind CSS 4** | Styling (`resources/css/app.css` + Vite plugin) |
| **Alpine.js** | Toggle tech stack proyek (terbundel di Livewire) |
| **Vite** | Build asset frontend (`vite.config.js`) |
| **SQLite / MySQL** | Database via `.env` |
| **Eloquent + SoftDeletes** | Model di `app/Models/` |
| **Migration + Seeder** | `database/migrations/`, `database/seeders/DatabaseSeeder.php` |
| **Validasi server-side** | `$this->validate()` di method Livewire |
| **File upload** | Avatar & gambar proyek via `WithFileUploads` + Storage |
| **Computed properties** | `#[Computed]` untuk load data profile/skills/dll |

**Pola arsitektur:** tidak ada controller terpisah. Satu route `/` → Blade layout → satu komponen Livewire yang menangani View Mode & Edit Mode + CRUD semua entitas.


## Cara Install & Menjalankan (Lokal)

### Prasyarat

- PHP 8.3+
- Composer
- Node.js & npm
- (Opsional) Laragon / XAMPP jika memakai MySQL

### Langkah Setup

```bash
# 1. Clone repository
git clone <url-repo-kamu>
cd PortfolioProject

# 2. Install dependency PHP
composer install

# 3. Salin file environment
cp .env.example .env

# 4. Generate app key
php artisan key:generate

# 5. Siapkan database
# Default memakai SQLite — buat file database:
# Windows (PowerShell): New-Item database/database.sqlite -ItemType File
# macOS/Linux: touch database/database.sqlite

# 6. Migrasi + seeder (data contoh)
php artisan migrate --seed

# 7. Link storage (untuk avatar & gambar proyek)
php artisan storage:link

# 8. Install & build frontend assets
npm install
npm run build
# atau untuk development: npm run dev

# 9. Jalankan server
php artisan serve
```

Buka browser: [http://127.0.0.1:8000](http://127.0.0.1:8000)

### Jika ingin memakai MySQL

Edit `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portfolio
DB_USERNAME=root
DB_PASSWORD=
```

Lalu buat database `portfolio` di MySQL, kemudian jalankan lagi:

```bash
php artisan migrate --seed
```

## Cara Pakai Aplikasi

1. **View Mode (default):** tampilan landing page portfolio (read-only).
2. Klik tombol **Edit Content** di navbar untuk masuk **Edit Mode**.
3. Di Edit Mode, setiap section menampilkan form CRUD (tambah / ubah / hapus).
4. Klik **Lihat Web** untuk kembali ke tampilan bersih View Mode.
5. Login / autentikasi **tidak wajib** untuk tugas ini.

## Struktur Database (ringkas)

- `profiles` — data diri (1 record)
- `skills` — keahlian
- `experiences` — pengalaman
- `education` — pendidikan & sertifikasi
- `projects` — proyek portfolio

Relasi: satu profile memiliki banyak skills / experiences / educations / projects (konten ditampilkan dari masing-masing tabel).

## Catatan

- Pastikan folder `storage` dan `bootstrap/cache` writable.
- Setelah upload avatar/gambar proyek, pastikan `php artisan storage:link` sudah dijalankan.
- Commit history dibuat bertahap sesuai ketentuan penilaian Git.
