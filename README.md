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
```bash
GET /attendances/export - Export ke Excel
GET /attendances/pdf    - Generate PDF report
```

---

## 🔌 API Endpoints

### Authentication
```
POST   /login           - Login pengguna
POST   /logout          - Logout pengguna
POST   /register        - Register (jika enabled)
```

### Dashboard
```
GET    /dashboard       - Dashboard utama
```

### Employees
```
GET    /employees                    - List employees
GET    /employees/{id}               - Get employee detail
POST   /employees                    - Create employee
PUT    /employees/{id}               - Update employee
DELETE /employees/{id}               - Delete employee
```

### Attendances
```
GET    /attendances                  - List attendances
GET    /attendances/{id}             - Get attendance detail
POST   /attendances                  - Create attendance
PUT    /attendances/{id}             - Update attendance
DELETE /attendances/{id}             - Delete attendance
GET    /attendances/export           - Export to Excel
```

### Shifts
```
GET    /shift-codes                  - List shift codes
GET    /shifts                       - List shifts
POST   /shifts                       - Create shift
PUT    /shifts/{id}                  - Update shift
DELETE /shifts/{id}                  - Delete shift
```

### Shift Assignments
```
GET    /employee-shift-assignments         - List assignments
POST   /employee-shift-assignments         - Create assignment
POST   /employee-shift-assignments/import  - Import from file
PUT    /employee-shift-assignments/{id}    - Update assignment
DELETE /employee-shift-assignments/{id}    - Delete assignment
```

### Master Data
```
GET    /locations                    - List locations
GET    /departments                  - List departments
GET    /branches                     - List branches
GET    /users                        - List users
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

## 🧪 Testing

Sistem menggunakan **Pest PHP** untuk testing.

### Menjalankan Tests

```bash
# Run semua tests
php artisan test

# Run specific test file
php artisan test tests/Feature/AttendanceTest.php

# Run with coverage
php artisan test --coverage

# Run watch mode
php artisan test --watch
```

### Contoh Test Structure
```
tests/
├── Feature/
│   ├── AttendanceTest.php
│   ├── EmployeeTest.php
│   └── ...
└── Unit/
    ├── ValidatorTest.php
    └── ...
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

## 🤝 Contributing

1. Fork repository
2. Buat branch feature: `git checkout -b feature/amazing-feature`
3. Commit changes: `git commit -m 'Add amazing feature'`
4. Push to branch: `git push origin feature/amazing-feature`
5. Buka Pull Request

### Code Style

Gunakan Laravel/PSR standards dengan:
```bash
composer run lint  # Check code style
composer run format  # Format code
```

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
| Project Manager | Rizky Pratama |
| Lead Developer | - |
| QA Engineer | - |

---

**Last Updated**: April 10, 2026  
**Status**: Active Development
