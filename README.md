# Attendance Management System

Sistem manajemen kehadiran karyawan yang komprehensif dengan fitur tracking sidik jari, penugasan shift, dan analitik real-time.

## 📋 Daftar Isi

- [Overview](#overview)
- [Fitur Utama](#fitur-utama)
- [Tech Stack](#tech-stack)
- [Requirements](#requirements)
- [Instalasi](#instalasi)
- [Struktur Project](#struktur-project)
- [Penggunaan](#penggunaan)
- [API Endpoints](#api-endpoints)
- [Database](#database)
- [Contributing](#contributing)

---

## 🎯 Overview

**Attendance Management System** adalah aplikasi web modern yang dirancang untuk mengelola kehadiran karyawan secara efisien. Sistem ini mengintegrasikan data sidik jari (fingerprint), jadwal kerja, dan penugasan shift untuk memberikan visibilitas penuh terhadap kehadiran dan produktivitas karyawan.

### Fitur Kunci:
- ✅ Dashboard analytics real-time
- ✅ Tracking kehadiran otomatis via fingerprint
- ✅ Manajemen shift dan jadwal kerja
- ✅ Import data massal dari Excel
- ✅ Laporan dan export PDF
- ✅ Multi-location dan multi-department support
- ✅ User role management

---

## ⭐ Fitur Utama

### 1. Dashboard & Analytics
- **Real-time Statistics**: Total karyawan aktif, hadir, terlambat, dan absen hari ini
- **7-Day Attendance Trend**: Visualisasi grafik tren kehadiran seminggu terakhir
- **Top Late Employees**: Identifikasi karyawan dengan keterlambatan tertinggi bulan ini
- **Department Performance**: Analisis tingkat keterlambatan per departemen
- **Import Status**: Monitoring status import shift assignment terbaru
- **Quick Access**: Shortcuts ke fitur-fitur utama

### 2. Employee Management
- Manajemen data karyawan (nama, NIK, departemen, lokasi)
- Status aktif/non-aktif
- Integrasi dengan departemen dan lokasi

### 3. Shift Management
- Definisi shift (pagi, siang, malam, dll)
- Shift codes dengan waktu masuk/keluar
- Aturan shift harian (shift day rules)
- Penugasan shift ke karyawan
- Shift groups untuk batch assignment

### 4. Attendance Tracking
- Tracking kehadiran manual atau otomatis
- Status: Hadir, Terlambat, Absen, Cuti, Hari Libur
- Historical data lengkap
- Fingerprint log integration

### 5. Fingerprint Integration
- Sinkronisasi data fingerprint dari device
- View detailed fingerprint logs
- Automatic attendance processing

### 6. Shift Assignment
- Bulk import shift assignments dari Excel
- Data validation otomatis
- Error tracking dan reporting
- Update/delete functionality

### 7. Master Data
- **Locations**: Lokasi kerja/kantor
- **Departments**: Departemen organisasi
- **Branches**: Cabang perusahaan
- **User Accounts**: Manajemen pengguna sistem

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|------------|
| **Framework** | Laravel 12.x |
| **Language** | PHP 8.2+ |
| **Frontend** | Blade Templates, Tailwind CSS |
| **Database** | MySQL/MariaDB |
| **Task Queue** | Laravel Queue |
| **PDF Generation** | DomPDF |
| **Excel** | PhpSpreadsheet |
| **Testing** | Pest PHP |
| **UI Framework** | Chart.js (Analytics) |
| **Build Tool** | Vite |

### Dependencies Utama:
```json
{
  "laravel/framework": "^12.0",
  "barryvdh/laravel-dompdf": "^3.1",
  "phpoffice/phpspreadsheet": "^5.5",
  "rap2hpoutre/fast-excel": "^5.6",
  "realrashid/sweet-alert": "^7.3"
}
```

---

## 📦 Requirements

- **PHP**: 8.2 atau lebih tinggi
- **Composer**: Latest version
- **Node.js**: 18.x atau lebih tinggi
- **npm**: 9.x atau lebih tinggi
- **MySQL/MariaDB**: 5.7 atau lebih tinggi
- **Git**: Optional, untuk version control

---

## 🚀 Instalasi

### 1. Clone Repository
```bash
git clone <repository-url>
cd attendance
```

### 2. Install Dependencies

**Backend (PHP)**:
```bash
composer install
```

**Frontend (Node.js)**:
```bash
npm install
```

### 3. Environment Configuration

Copy file environment dan generate key:
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` dan sesuaikan konfigurasi:
```env
APP_NAME="Attendance System"
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=attendance
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
```

### 4. Database Setup

```bash
# Create database
mysql -u root -p -e "CREATE DATABASE attendance;"

# Run migrations
php artisan migrate

# Seed data (optional)
php artisan db:seed
```

### 5. Aplikasi Setup (Automated)

Atau gunakan script otomatis:
```bash
composer run setup
```

### 6. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 7. Jalankan Aplikasi

**Development Server**:
```bash
php artisan serve
```

Akses di: `http://localhost:8000`

**Queue Worker** (untuk background jobs):
```bash
php artisan queue:work
```

---

## 📁 Struktur Project

```
attendance/
├── app/
│   ├── Console/
│   │   └── Commands/              # Custom artisan commands
│   ├── Http/
│   │   ├── Controllers/           # Aplikasi controllers
│   │   │   ├── DashboardController.php
│   │   │   ├── EmployeeController.php
│   │   │   ├── AttendanceController.php
│   │   │   ├── EmployeeShiftAssignmentController.php
│   │   │   └── ... (18+ controllers)
│   │   └── Requests/              # Form validation requests
│   ├── Models/
│   │   ├── Employee.php
│   │   ├── Attendance.php
│   │   ├── EmployeeShiftAssignment.php
│   │   ├── Shift.php
│   │   ├── ShiftCode.php
│   │   ├── Department.php
│   │   ├── Location.php
│   │   ├── Branch.php
│   │   └── ... (13+ models)
│   ├── Services/
│   │   ├── AttendanceProcessService.php     # Business logic
│   │   ├── EmployeeShiftAssignmentService.php
│   │   ├── FingerprintSyncService.php
│   │   ├── BranchService.php
│   │   └── ... (10+ services)
│   ├── Providers/
│   │   └── AppServiceProvider.php
│   └── View/
│       └── Components/            # Reusable Blade components
│
├── database/
│   ├── migrations/                # Database schema
│   ├── seeders/                   # Database seeders
│   └── factories/                 # Model factories
│
├── resources/
│   ├── css/                       # Tailwind CSS
│   ├── js/                        # JavaScript files
│   └── views/
│       ├── dashboard/             # Dashboard views
│       ├── attendances/
│       ├── employees/
│       ├── employee_shift_assignments/
│       ├── fingerprint_logs/
│       ├── shift_codes/
│       ├── branches/
│       ├── departments/
│       ├── locations/
│       ├── users/
│       ├── auth/
│       ├── layouts/               # Layout utama
│       └── components/            # Reusable components
│
├── routes/
│   ├── web.php                    # Web routes
│   └── console.php                # Console commands
│
├── config/                        # Configuration files
├── tests/                         # Test files (Pest)
├── storage/                       # File storage
├── public/                        # Public assets
│
├── composer.json                  # PHP dependencies
├── package.json                   # Node.js dependencies
├── vite.config.js                 # Vite configuration
├── phpunit.xml                    # PHPUnit config
└── README.md                      # Dokumentasi
```

---

## 👥 User Roles & Permissions

Sistem supports multiple user roles:

| Role | Akses |
|------|-------|
| **HR** | Full access semua modul |
| **IT** | Full access semua modul |

---

## 📖 Penggunaan

### Dashboard
- Akses halaman utama untuk overview kehadiran real-time
- Lihat statistik karyawan hadir/terlambat/absen hari ini
- Monitor tren kehadiran 7 hari terakhir
- Identifikasi top late employees dan departemen

### Manajemen Karyawan

**Create Employee**:
```bash
POST /employees - Tambah karyawan baru
```

**View Employees**:
```bash
GET /employees - Lihat daftar karyawan
GET /employees/{id} - Lihat detail karyawan
```

**Update/Delete**:
```bash
PUT /employees/{id} - Update data karyawan
DELETE /employees/{id} - Hapus karyawan
```

### Tracking Kehadiran

**Manual Attendance**:
1. Navigasi ke "Attendance List"
2. Klik "Add Attendance"
3. Pilih karyawan dan status
4. Submit

**Fingerprint Integration**:
1. Sync fingerprint device
2. Sistem otomatis memproses kehadiran
3. Review di "Fingerprint Logs"

### Import Shift Assignment

**Via Excel**:
1. Prepare file Excel dengan kolom: Employee Name, Shift Code, Date
2. Navigasi ke "Employee Shift Assignments"
3. Klik "Import"
4. Upload file
5. Sistem validate dan import data

**File Format**:
```
Employee Name | Shift Code | Date
John Doe      | SA1        | 01/04/2026
Jane Smith    | SA2        | 01/04/2026
```

### Generate Reports

**Export Attendance**:
Di halaman list kehadiran, klik tombol "Export PDF" untuk mengunduh report dalam format PDF.

```
File: resources/views/attendances/index.blade.php
Route: GET /attendances/export-pdf
```


---

## 🌐 Web Routes

Aplikasi menggunakan **traditional Laravel web routes** (bukan REST API). Semua fitur diakses melalui web interface dengan Blade templates.

### Authentication Routes
```
GET    /                    - Login page
POST   /login               - Process login
POST   /logout              - Logout user
```

### Dashboard
```
GET    /dashboard           - Dashboard utama dengan analytics
```

### Employee Management
```
GET    /employees                    - List semua karyawan
GET    /employees/create             - Form tambah karyawan
POST   /employees                    - Simpan karyawan baru
GET    /employees/{id}/edit          - Form edit karyawan
PUT    /employees/{id}               - Update karyawan
DELETE /employees/{id}               - Hapus karyawan
POST   /employees/import             - Import karyawan dari Excel
```

### Attendance Management
```
GET    /attendances                  - List kehadiran
GET    /attendances/create           - Form tambah kehadiran
POST   /attendances                  - Simpan kehadiran
GET    /attendances/{id}/edit        - Form edit kehadiran
PUT    /attendances/{id}             - Update kehadiran
DELETE /attendances/{id}             - Hapus kehadiran
GET    /attendances/export-pdf       - Export PDF report
```

### Shift Management
```
GET    /shift_groups                 - List shift groups
POST   /shift_groups                 - Buat shift group
GET    /shift_definitions            - List definisi shift
POST   /shift_definitions            - Buat definisi shift
GET    /shift_codes                  - List shift codes
POST   /shift_codes                  - Buat shift code
```

### Shift Assignment
```
GET    /employee_shift_assignments              - List penugasan shift
GET    /employee_shift_assignments/create       - Form tambah penugasan
POST   /employee_shift_assignments              - Simpan penugasan
GET    /employee_shift_assignments/{id}/edit    - Form edit penugasan
PUT    /employee_shift_assignments/{id}         - Update penugasan
DELETE /employee_shift_assignments/{id}         - Hapus penugasan
POST   /employee_shift_assignments/import       - Import penugasan dari Excel
```

### Process Attendance
```
GET    /process_attendances         - List proses kehadiran
GET    /process_attendances/create  - Form proses manual
POST   /process_attendances/process - Process kehadiran otomatis
```

### Fingerprint Integration
```
GET    /fingerprint              - View fingerprint logs
POST   /fingerprint/sync         - Sync dari device fingerprint
```

### Master Data
```
GET    /locations                    - Kelola lokasi kantor
POST   /locations                    - Tambah lokasi
PUT    /locations/{id}               - Update lokasi
DELETE /locations/{id}               - Hapus lokasi

GET    /departments                  - Kelola departemen
POST   /departments                  - Tambah departemen
PUT    /departments/{id}             - Update departemen
DELETE /departments/{id}             - Hapus departemen

GET    /branches                     - Kelola cabang
POST   /branches                     - Tambah cabang
PUT    /branches/{id}                - Update cabang
DELETE /branches/{id}                - Hapus cabang

GET    /users                        - Manajemen user
POST   /users                        - Buat user baru
PUT    /users/{id}                   - Update user
DELETE /users/{id}                   - Hapus user
```

### User Profile
```
PATCH  /profile                      - Update profil user
PATCH  /password                     - Ubah password user
```

---

## 💾 Database

### Main Tables

#### `employees`
```sql
- id (int, primary key)
- name (string)
- nik (string, unique)
- email (string, unique)
- phone (string, nullable)
- department_id (foreign key)
- location_id (foreign key)
- is_active (boolean)
- created_at, updated_at
```

#### `attendances`
```sql
- id (int, primary key)
- employee_id (foreign key)
- attendance_date (date)
- status (enum: present, late, absent, day_off, holiday)
- check_in_time (time, nullable)
- check_out_time (time, nullable)
- notes (text, nullable)
- created_at, updated_at
```

#### `employee_shift_assignments`
```sql
- id (int, primary key)
- employee_id (foreign key)
- shift_code_id (foreign key)
- date (date)
- is_active (boolean)
- created_by (foreign key)
- created_at, updated_at
```

#### `shifts`
```sql
- id (int, primary key)
- name (string)
- description (text, nullable)
- created_at, updated_at
```

#### `shift_codes`
```sql
- id (int, primary key)
- shift_id (foreign key)
- code (string, unique)
- name (string)
- start_time (time)
- end_time (time)
- tolerance_minutes (int, default: 0)
- created_at, updated_at
```

#### `departments`
```sql
- id (int, primary key)
- name (string, unique)
- description (text, nullable)
- created_at, updated_at
```

#### `locations`
```sql
- id (int, primary key)
- name (string)
- address (text)
- created_at, updated_at
```

#### `branches`
```sql
- id (int, primary key)
- name (string)
- address (text, nullable)
- created_at, updated_at
```

#### `users`
```sql
- id (int, primary key)
- name (string)
- email (string, unique)
- password (hashed)
- role (enum: admin, manager, hr, supervisor, employee)
- employee_id (foreign key, nullable)
- created_at, updated_at
```
---

## 🔧 Configuration

### Konfigurasi Database

Edit `database/migrations/` untuk custom schema.

### Konfigurasi Queue

Edit `.env`:
```env
QUEUE_CONNECTION=database  # atau redis, sync
```

### Konfigurasi Mail

Untuk email notifications:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
```

---

## 📋 Checklist Setup

Pastikan sudah melakukan:

- [ ] Install PHP & Composer
- [ ] Install Node.js & npm
- [ ] Clone repository
- [ ] Copy `.env.example` ke `.env`
- [ ] Generate app key: `php artisan key:generate`
- [ ] Setup database (create & migrate)
- [ ] Install dependencies: `composer install` & `npm install`
- [ ] Build assets: `npm run build`
- [ ] Seed data (optional): `php artisan db:seed`
- [ ] Run server: `php artisan serve`
- [ ] Start queue worker (if needed): `php artisan queue:work`

---

## 🐛 Troubleshooting

### Database Connection Error
```bash
# Verify database exists
mysql -u root -p -e "SHOW DATABASES;"

# Re-run migrations
php artisan migrate:fresh --seed
```

### Permission Denied
```bash
# Fix storage permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### Asset Not Loading
```bash
# Rebuild assets
npm run build

# Or watch mode for development
npm run dev
```

### Composer/NPM Issues
```bash
# Clear caches
composer clear-cache
npm cache clean --force

# Reinstall
rm -rf vendor node_modules
composer install && npm install
```

---

## 📚 Dokumentasi Tambahan

- [Laravel Documentation](https://laravel.com/docs)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Chart.js](https://www.chartjs.org/docs/latest/)
- [PhpSpreadsheet](https://phpspreadsheet.readthedocs.io/)

---

## 📝 Changelog

### v1.0.0 (2026-04-10)
- ✅ Initial release
- ✅ Dashboard dengan analytics
- ✅ Employee management
- ✅ Attendance tracking
- ✅ Shift management
- ✅ Fingerprint integration
- ✅ Shift assignment import
- ✅ Report generation

---

## 📧 Support & Contact

Untuk pertanyaan atau laporan bug:
- Email: support@company.com
- Telegram: @company_support
- Slack: #attendance-system

---

## 📄 License

Project ini dilisensikan di bawah MIT License. Lihat file `LICENSE` untuk detail.

---

## 👨‍💻 Tim Development

| Role | Name |
|------|------|
| Project Manager + Web Developer | Rizky Pratama |

---

**Last Updated**: April 10, 2026  
**Status**: Active Development
