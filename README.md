# Staff Performance Management System

A Laravel-based system for tracking staff performance based on attendance and training session participation, with configurable action triggers and comprehensive reporting.

## Features

- **Google OAuth Authentication** - Secure login via Google
- **Staff Management** - CRUD operations for staff with departments and designations
- **Training Sessions** - Create sessions, assign staff, track notifications and attendance
- **Attendance Import** - Import monthly attendance from Excel files
- **Configurable Action Triggers** - Set thresholds for automatic action creation
- **Action Management** - Track actions, upload documents, collect feedback
- **Dashboard** - Real-time stats, charts, and staff at risk monitoring
- **Reports & Exports** - Excel and PDF reports for management presentations

## Requirements

- PHP 8.2+
- Composer 2.x
- MySQL 8.0+ or MariaDB 10.6+
- Node.js 18+ (for asset compilation)

## Installation

### 1. Clone and Install Dependencies

```bash
cd staff-performance
composer install
npm install
```

### 2. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and configure:

```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=staff_performance
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Google OAuth
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URL="${APP_URL}/auth/google/callback"
```

### 3. Google OAuth Setup

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing
3. Enable the Google+ API
4. Go to Credentials > Create Credentials > OAuth Client ID
5. Configure consent screen
6. Add authorized redirect URI: `http://your-app-url/auth/google/callback`
7. Copy Client ID and Secret to `.env`

### 4. Database Setup

```bash
# Create database
mysql -u root -p -e "CREATE DATABASE staff_performance"

# Run migrations
php artisan migrate

# Seed default data
php artisan db:seed
```

### 5. Storage Link

```bash
php artisan storage:link
```

### 6. Build Assets

```bash
npm run build
```

### 7. Start Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000`

## Default Data

The seeders create:
- 10 Departments (Administration, HR, Finance, etc.)
- 10 Designations (Manager, Officer, Trainee, etc.)
- 6 Action Types (Warning Letter, Meeting, Training Assignment, etc.)
- 6 Default Triggers (High Absence, Excessive Late Days, Missed Training, etc.)

## User Roles

- **Admin** - Full access to all features including settings
- **Manager** - Can manage staff, training, attendance, and actions
- **Viewer** - Read-only access to dashboard and reports

The first user to log in via Google will be a viewer. Change roles in Settings > Users.

## Usage Guide

### Managing Staff

1. Go to **Staff** menu
2. Click **Add Staff** to create new staff members
3. Required fields: Name, Record Card Number
4. Optional: Department, Designation, Email, Phone

### Creating Training Sessions

1. Go to **Training** menu
2. Click **Add Training**
3. Fill in details and select staff to assign
4. After creating, use **Mark Attendance** to record attendance

### Importing Attendance

1. Go to **Attendance** menu
2. Click **Import Attendance**
3. Download the template Excel file
4. Fill in the data and upload
5. Select year and month for the import

**Template columns:**
- `record_card_number` - Staff record card number
- `days_present` - Number of present days
- `days_absent` - Number of absent days
- `days_leave` - Number of leave days
- `days_late` - Number of late days
- `total_working_days` - Total working days in month

### Configuring Action Triggers

1. Go to **Settings > Triggers** (Admin only)
2. Create or edit triggers with:
   - Trigger type (Attendance/Training)
   - Condition (Absent Days >= 6, Late Days >= 10, etc.)
   - Action type to create when triggered
   - Period type (Monthly/Quarterly/Yearly)

### Processing Triggers

1. After importing attendance or recording training attendance
2. Go to **Actions** menu
3. Click **Process Triggers** button
4. Select year and month
5. System will automatically create actions for staff who exceed thresholds

### Managing Actions

1. View all actions in **Actions** menu
2. Click on an action to:
   - Update status (Pending → In Progress → Completed)
   - Upload documents (warning letters, meeting notes)
   - Add feedback from staff or manager

### Generating Reports

1. Go to **Reports** menu
2. Choose report type:
   - Staff Performance Report
   - Attendance Summary
   - Training Summary
   - Action Report
3. View online or export to Excel/PDF

## File Structure

```
staff-performance/
├── app/
│   ├── Models/           # Eloquent models
│   ├── Http/
│   │   ├── Controllers/  # Request handlers
│   │   └── Middleware/   # Role checking
│   ├── Services/         # Business logic
│   ├── Imports/          # Excel imports
│   └── Exports/          # Excel exports
├── database/
│   ├── migrations/       # Database schema
│   └── seeders/          # Default data
├── resources/views/      # Blade templates
└── routes/web.php        # Route definitions
```

## Customization

### Adding New Action Types

1. Go to Settings > Action Types
2. Create new type with code, name, description
3. Set whether documents or feedback are required

### Modifying Triggers

1. Go to Settings > Triggers
2. Adjust threshold values as needed
3. Enable/disable triggers with toggle

### Adding Departments/Designations

1. Go to Settings > Departments or Designations
2. Add new entries as needed

## Troubleshooting

### Google Login Issues
- Verify redirect URI matches exactly in Google Console
- Check GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET in .env
- Ensure APP_URL is correct

### Import Errors
- Verify record card numbers exist in staff table
- Check Excel column headers match expected names
- Ensure numeric values for days

### Permission Issues
- Check user role in Settings > Users
- Admin role required for settings access

## License

This project is open-sourced software.

## Support

For issues or questions, please contact your system administrator.
