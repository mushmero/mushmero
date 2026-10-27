# Resumed — Professional Resume & Portfolio Platform

**Resumed** is an elegant, powerful, and user-friendly personal resume and online portfolio management platform built with **Laravel 12** and **AdminLTE 3**. 

Designed as a **single-user platform with multi-persona support**, Resumed allows a professional to maintain multiple specialized profiles (e.g., *Full-Stack Developer*, *DevOps Consultant*, *Technical Lead*) under one roof and switch which persona is actively displayed on the public landing page or exported as a PDF resume.

---

## 🌟 Comprehensive Feature Showcase

### 🎭 Single-User & Multi-Persona Engine
- **Single-User Ownership**: Designed exclusively for a single professional account. Public registration is disabled to ensure your site is completely private and personal.
- **Multiple Personas**: Create and maintain distinct personas tailored for different industries, roles, or consulting specializations.
- **Instant Persona Switching**: Toggle the active persona with a single click using the interactive AJAX status switch.
- **Live Dynamic Portfolio**: The public landing page (`/`) automatically renders the bio, photo, work history, skills, and projects of whichever persona is currently active.

---

### 📝 Comprehensive Resume Modules
Every module automatically associates records with your active persona while maintaining complete historical records:

- **Personal Profile**:
  - Full name, professional tagline, personal email, phone number, and website.
  - Avatar image upload with automatic asset management and preview.
  - Soft-delete and restore capabilities.
- **Work Experiences**:
  - Detailed employment history: role title, employer name, start and end dates (or "Current"), and rich project/job descriptions.
  - Chronological ordering on public views and exported resumes.
- **Education & Credentials**:
  - Academic history: degree or course name, institution, graduation period, and level classification.
- **Skills & Proficiencies**:
  - Skill name with interactive percentage progress indicators.
  - Custom FontAwesome icons mapped to each skill category.
- **Projects Showcase**:
  - Project title, status tag (*Completed*, *In Progress*, *On Hold*), and detailed descriptions.
- **Languages**:
  - Spoken and written language competencies with customizable proficiency ratings (*Native*, *Professional*, *Conversational*).
- **Social & Professional Networks**:
  - Social profiles with icon badges (GitHub, LinkedIn, Twitter/X, Dribbble, Medium, etc.).
- **Career Objectives**:
  - Mission statements, career summaries, and executive bio paragraphs.
- **Personal Interests**:
  - Hobbies, personal interests, and extracurricular passions.

---

### 📄 Resume Templates & PDF Export
- **Customizable Templates**: Select from different resume layout templates to suit different applications.
- **Live HTML Preview**: Review your formatted resume directly in your browser before exporting.
- **High-Fidelity PDF Generation**: Generate clean, print-ready, high-resolution PDF resumes with one click.
- **PDF Layout & Geometry Settings**:
  - Granular margin adjustments (top, bottom, left, and right in mm/points).
  - Configurable running headers and footers.
- **Persona-Bound Exporting**: Generated PDFs automatically adapt to the specific persona you are currently working on.

---

### ⚙️ Lookups & Configuration Settings
Customize all system options through dedicated admin panels:
- **Education Levels**: Define customized qualification tiers (e.g., High School, Diploma, Bachelor's, Master's, Doctorate).
- **Language Proficiencies**: Configure proficiency scales (e.g., Elementary, Working, Fluent, Native).
- **Project Statuses**: Customize project lifecycle labels.
- **Social Icons**: Manage font-awesome classes and icon definitions.
- **PDF Settings**: Manage document templates and default page formatting.

---

### 🌐 Public Landing Page & Visitor Engagement
- **Responsive Web Portfolio**: Beautiful, mobile-friendly landing page presenting your active persona.
- **Interactive Leads / Contact Form**: Public visitors and recruiters can submit interest messages directly through the landing page.
- **Visitor Analytics**: Built-in tracking capturing visitor IPs, user agents, and page impression logs.

---

### 🖥️ Administration & Security Features
- **AdminLTE 3 Dashboard**: Clean, responsive administrative control panel with light/dark theme widgets.
- **Activity Audit Trail**: Automatically tracks create, update, and delete actions across modules via Spatie Activitylog.
- **Inactivity Auto-Logout**: Automatic session protection logging out idle sessions to protect resume data.
- **Private Access Control**: Administrative routes protected by authentication middleware with public registration disabled.

---

## 📋 System Requirements

- **PHP**: `^8.4` or `^8.5` (Fully tested on PHP 8.5)
- **Extensions**: `pdo_mysql`, `pdo_sqlite`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `gd` / `imagick`
- **Composer**: `^2.2` (Composer 2.10+ recommended)
- **Node.js & NPM**: Node `^18.0` / `^20.0` (for Vite asset compilation)
- **Database**: MySQL 8.0+ / MariaDB 10.4+ / SQLite (testing)

---

## 🛠️ Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone https://github.com/your-username/resumed.git
   cd resumed
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install --optimize-autoloader --no-dev
   ```

3. **Configure Environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure your Database in `.env`:**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=resumed
   DB_USERNAME=your_db_user
   DB_PASSWORD=your_db_password
   ```

5. **Run Migrations & Seeders:**
   ```bash
   php artisan migrate --seed
   ```
   *The default administrator account will be created via `AdminUserSeeder`.*

6. **Install Frontend Dependencies & Build Assets:**
   ```bash
   npm install
   npm run build
   ```

7. **Start the local development server:**
   ```bash
   php artisan serve
   ```

8. **Access the application:**
   - **Public Portfolio**: `http://localhost:8000/`
   - **Admin Login**: `http://localhost:8000/login`

---

## 🧪 Running Tests

The application includes automated unit and feature tests covering authentication, single-user multi-persona lifecycles, and helper utilities:

```bash
# Run tests with in-memory SQLite
php artisan test

# Or run directly via PHPUnit
php -r "require 'vendor/autoload.php'; (new PHPUnit\TextUI\Application)->run(['phpunit', '--no-configuration', 'tests/Unit/HelperAndModelTest.php', 'tests/Feature/SecurityAndAuthTest.php']);"
```

---

## 📄 License

This project is open-source software licensed under the [MIT License](LICENSE).
