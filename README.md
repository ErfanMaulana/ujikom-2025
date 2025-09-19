# 🎓 Ujikom 2025 - Laravel Web Application

<div align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
  
  <h3>Aplikasi Web untuk Ujian Kompetensi Keahlian</h3>
  <p><strong>SMKN 1 Ciamis - Tahun 2025</strong></p>
  
  ![Laravel](https://img.shields.io/badge/Laravel-12.28.1-red?style=flat-square&logo=laravel)
  ![PHP](https://img.shields.io/badge/PHP-8.2+-blue?style=flat-square&logo=php)
  ![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange?style=flat-square&logo=mysql)
  ![Status](https://img.shields.io/badge/Status-Development-yellow?style=flat-square)
</div>

---

##  Biodata Peserta

| **Biodata** | **Keterangan** |
|-------------|----------------|
| **Nama Lengkap** | Erfan Eka Maulana |
| **NIS** | 232410549 |
| **NISN** | 0088223031 |
| **Tempat, Tanggal Lahir** | Ciamis, 12 September 2007 |
| **Kelas** | XII PPLG (Pengembangan Perangkat Lunak dan Gim) |
| **Sekolah** | SMKN 1 Ciamis |
| **Tahun Ujikom** | 2025 |

---

## 📋 Deskripsi Proyek

Aplikasi web berbasis Laravel 12 yang dikembangkan sebagai bagian dari **Ujian Kompetensi Keahlian (Ujikom) 2025** untuk kompetensi keahlian **Pengembangan Perangkat Lunak dan Gim (PPLG)** di SMKN 1 Ciamis.

### 🎯 Tujuan Proyek
- Mendemonstrasikan kemampuan dalam pengembangan aplikasi web
- Mengimplementasikan konsep MVC (Model-View-Controller)
- Menerapkan best practices dalam programming
- Memenuhi standar kompetensi keahlian PPLG

---

## 🚀 Teknologi yang Digunakan

- **Backend Framework**: Laravel 12.28.1
- **Database**: MySQL 8.0+
- **Frontend**: Blade Templates, Bootstrap 5
- **Language**: PHP 8.2+
- **Tools**: Composer, NPM, Vite
- **Environment**: Laragon (Windows)

---

## 📦 Instalasi & Setup

### Prerequisites
- PHP >= 8.2
- Composer
- MySQL/MariaDB
- Node.js & NPM

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/ErfanMaulana/ujikom-2025.git
   cd ujikom-2025
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**
   - Buat database MySQL: `ujikom_2025_erfan`
   - Update konfigurasi di `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=ujikom_2025_erfan
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Run Migrations**
   ```bash
   php artisan migrate
   ```

6. **Start Development Server**
   ```bash
   php artisan serve
   ```

---

## 🏗️ Struktur Aplikasi

### Models & Database
- **User**: Sistem autentikasi pengguna
- **Category**: Kategori konten/data
- **Jobs**: Queue system untuk background tasks
- **Cache**: Sistem caching aplikasi

### Controllers
- **DashboardController**: Mengelola halaman dashboard
- **HomeController**: Mengelola halaman utama

### Features (Planned)
- [ ] Authentication System (Login/Register)
- [ ] User Management
- [ ] Dashboard Admin
- [ ] Data Management (CRUD)
- [ ] Responsive Design
- [ ] Security Features

---

## 📊 Status Pengembangan

### ✅ Completed
- [x] Laravel 12 Installation
- [x] Database Configuration
- [x] Basic Project Structure
- [x] Environment Setup

### 🔄 In Progress
- [ ] Authentication System
- [ ] UI/UX Design
- [ ] Core Features Development

### 📅 Timeline
- **Start Date**: 15 September 2025
- **Target Completion**: [Sesuai jadwal ujikom]
- **Testing Phase**: [1 minggu sebelum ujikom]

---

## 🎨 Design & UI

Aplikasi menggunakan design modern dan responsive dengan:
- **Color Scheme**: Professional blue & white
- **Framework CSS**: Bootstrap 5
- **Icons**: Font Awesome
- **Typography**: Inter/Roboto fonts

---

## 🧪 Testing

```bash
# Run Unit Tests
php artisan test

# Run Feature Tests
php artisan test --feature

# Check Code Coverage
php artisan test --coverage
```

---

## 📞 Kontak

**Erfan Eka Maulana**
- **Sekolah**: SMKN 1 Ciamis
- **Kelas**: XII PPLG
- **GitHub**: [ErfanMaulana](https://github.com/ErfanMaulana)
- **Repository**: [ujikom-2025](https://github.com/ErfanMaulana/ujikom-2025)

---

## 📝 Lisensi

Proyek ini dikembangkan untuk keperluan **Ujian Kompetensi Keahlian 2025** dan dilindungi oleh ketentuan akademik SMKN 1 Ciamis.

---

<div align="center">
  <p><strong>SMKN 1 Ciamis - SMK Negeri 1 Ciamis Yakin Bisa!!</strong></p>
  <p><em>© 2025 Erfan Eka Maulana - XII PPLG</em></p>
</div>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
