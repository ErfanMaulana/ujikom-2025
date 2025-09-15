# Ujikom 2025 Erfan - Laravel 12 Project

## Deskripsi Proyek
Proyek Laravel 12 untuk Ujikom 2025 yang dikembangkan menggunakan framework Laravel terbaru.

## Requirements
- PHP >= 8.2
- Composer
- MySQL/MariaDB
- Node.js & NPM (untuk asset compilation)

## Instalasi

### 1. Clone Repository
```bash
git clone <repository-url>
cd ujikom-2025-erfan
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup
- Buat database MySQL dengan nama `ujikom_2025_erfan`
- Update konfigurasi database di file `.env`
- Jalankan migrasi:
```bash
php artisan migrate
```

### 5. Development Server
```bash
php artisan serve
```

## Fitur Utama
- Authentication System
- Dashboard Admin
- User Management
- [Tambahkan fitur sesuai kebutuhan]

## Struktur Database
### Users Table
- id (Primary Key)
- name
- email
- email_verified_at
- password
- remember_token
- created_at
- updated_at

### Cache Table
- key (Primary Key)
- value
- expiration

### Jobs Table
- id (Primary Key)
- queue
- payload
- attempts
- reserved_at
- available_at
- created_at

## Development Guidelines
1. Gunakan PSR-12 coding standard
2. Write tests untuk setiap feature
3. Gunakan migration untuk perubahan database
4. Follow Laravel best practices

## Kontributor
- Erfan (Developer)

## License
Private License - Ujikom 2025