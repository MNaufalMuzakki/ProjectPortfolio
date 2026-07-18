# Project Portfolio

Portfolio pribadi dengan CRUD di satu halaman (View Mode & Edit Mode).  
Stack: **Laravel + Livewire + Tailwind CSS**.

**Repo:** https://github.com/MNaufalMuzakki/ProjectPortfolio

## Fitur

- Satu halaman, dua mode: View & Edit (tanpa dashboard terpisah)
- CRUD: Profile, Skills, Experiences, Educations, Projects
- Data dari database, langsung tampil di landing page
- Validasi server-side + seeder contoh

## Cara Menjalankan

Prasyarat: PHP 8.3+, Composer, Node.js

```bash
git clone https://github.com/MNaufalMuzakki/ProjectPortfolio.git
cd ProjectPortfolio

composer install
cp .env.example .env
php artisan key:generate

# Buat database SQLite (Windows PowerShell):
New-Item database/database.sqlite -ItemType File -Force

# macOS / Linux:
# touch database/database.sqlite

php artisan migrate --seed
php artisan storage:link

npm install
npm run build

php artisan serve
```

Buka: http://127.0.0.1:8000

## Cara Pakai

1. Halaman terbuka dalam **View Mode** (landing page).
2. Klik **Edit Content** di navbar → muncul form CRUD tiap section.
3. Klik **Lihat Web** → kembali ke tampilan bersih.

Login tidak diperlukan.
