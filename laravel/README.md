# MedIntern — Medical Internship Management System

A complete Laravel 11 + Bootstrap 5 web application for managing medical student internship records.

---

## 🏗 Architecture

```
Service → Request → Controller
```

- **Models** — Eloquent with relationships
- **Services** — Business logic (`UserService`, `ReportService`)
- **Controllers** — Thin, delegates to services
- **Middleware** — Role-based access control (`RoleMiddleware`)

---

## 👥 Roles

| Role | Created By | Capabilities |
|------|-----------|-------------|
| `super_admin` | Seeded | Create/manage supervisors, view all students |
| `supervisor` | Super Admin | Create/manage students, review & approve reports |
| `student` | Supervisor | Submit daily reports, add notes, generate full report |

---

## 📦 Requirements

- PHP >= 8.2
- Composer
- MySQL / MariaDB
- Node.js (optional, for asset compilation)

---

## 🚀 Installation

### Step 1 — Create fresh Laravel project

```bash
composer create-project laravel/laravel medintern
cd medintern
```

### Step 2 — Copy generated files

Copy all files from this repository into your Laravel project, preserving the directory structure:

```
app/
  Http/
    Controllers/
      AuthController.php
      SuperAdminController.php
      SupervisorController.php
      StudentController.php
    Middleware/
      RoleMiddleware.php
  Models/
    User.php
    Student.php
    Report.php
    Attachment.php
    ReportNote.php
  Services/
    UserService.php
    ReportService.php

bootstrap/
  app.php

database/
  migrations/
    2024_01_01_000001_create_users_table.php
    2024_01_01_000002_create_students_table.php
    2024_01_01_000003_create_reports_table.php
    2024_01_01_000004_create_attachments_table.php
    2024_01_01_000005_create_report_notes_table.php
  seeders/
    DatabaseSeeder.php
    SuperAdminSeeder.php

resources/views/
  layouts/app.blade.php
  auth/login.blade.php
  admin/
    dashboard.blade.php
    supervisors/ (index, create, edit)
    students/ (index)
  supervisor/
    dashboard.blade.php
    students/ (index, create, edit)
    reports/ (index, show)
  student/
    dashboard.blade.php
    reports/ (index, create, edit, show, full-report)

routes/
  web.php
```

### Step 3 — Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` with your database credentials:

```env
APP_NAME="MedIntern"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=medintern
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 4 — Create database

```sql
CREATE DATABASE medintern CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 5 — Run migrations and seed

```bash
php artisan migrate --seed
```

### Step 6 — Create storage link (for file uploads)

```bash
php artisan storage:link
```

### Step 7 — Start server

```bash
php artisan serve
```

Visit: **http://localhost:8000**

---

## 🔐 Default Login

| Field | Value |
|-------|-------|
| Email | `ashley7520charles@gmail.com` |
| Password | `admin123@` |

> ⚠️ Change this password immediately after first login.

---

## 📁 File Upload Configuration

Uploaded attachments are stored in `storage/app/public/attachments/`.

Allowed file types: `PDF`, `DOC`, `DOCX`, `JPG`, `JPEG`, `PNG`  
Maximum file size: **10MB per file**

To change limits, update `StudentController.php`:
```php
'attachments.*' => ['nullable', 'file', 'max:10240', 'mimes:pdf,doc,docx,jpg,jpeg,png'],
```

---

## 📋 Database Schema

```
users
  id, name, email, phone, password, role (enum), is_active, timestamps

students
  id, user_id (FK), school, student_number, supervisor_id (FK → users),
  internship_start_date, internship_end_date, timestamps

reports
  id, student_id (FK), report (text), date_submitted, status (enum), timestamps

attachments
  id, report_id (FK), file_name, file_path, file_type, document_name, file_size, timestamps

report_notes
  id, report_id (FK), user_id (FK), note (text), timestamps
```

---

## 🗂 Key Routes

### Auth
| Method | URL | Description |
|--------|-----|-------------|
| GET | `/login` | Login page |
| POST | `/login` | Authenticate |
| POST | `/logout` | Sign out |

### Super Admin (`/admin/*`)
| Method | URL | Description |
|--------|-----|-------------|
| GET | `/admin/dashboard` | Dashboard |
| GET | `/admin/supervisors` | List supervisors |
| GET | `/admin/supervisors/create` | Create form |
| POST | `/admin/supervisors` | Store supervisor |
| GET | `/admin/supervisors/{id}/edit` | Edit form |
| PUT | `/admin/supervisors/{id}` | Update supervisor |
| PATCH | `/admin/supervisors/{id}/toggle` | Toggle active status |
| GET | `/admin/students` | View all students |

### Supervisor (`/supervisor/*`)
| Method | URL | Description |
|--------|-----|-------------|
| GET | `/supervisor/dashboard` | Dashboard |
| GET | `/supervisor/students` | My students |
| POST | `/supervisor/students` | Create student |
| GET | `/supervisor/reports` | All reports (with filters) |
| GET | `/supervisor/reports/{id}` | Review a report |
| PATCH | `/supervisor/reports/{id}/status` | Approve / Decline |
| POST | `/supervisor/reports/{id}/notes` | Add note |

### Student (`/student/*`)
| Method | URL | Description |
|--------|-----|-------------|
| GET | `/student/dashboard` | Dashboard |
| GET | `/student/reports` | My reports |
| GET | `/student/reports/create` | Submit form |
| POST | `/student/reports` | Submit report |
| GET | `/student/reports/{id}` | View report |
| GET | `/student/reports/{id}/edit` | Edit (pending only) |
| PUT | `/student/reports/{id}` | Update report |
| POST | `/student/reports/{id}/notes` | Add note |
| DELETE | `/student/attachments/{id}` | Delete attachment |
| GET | `/student/report/generate` | Full internship report |

---

## 🎨 UI Features

- Clean sidebar navigation per role
- Responsive Bootstrap 5 layout
- Status badges (Pending / Approved / Declined)
- Drag-and-drop file upload
- Report filters (by student, status, date range)
- Full internship report with print/PDF support
- Flash success/error messages
- Confirmation dialogs for destructive actions

---

## 📄 License

MIT License — Free to use and modify.