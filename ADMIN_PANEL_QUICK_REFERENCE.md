# Admin Panel Quick Reference

## Quick Start

### 1. Run Migrations & Seeder
```bash
php artisan migrate
php artisan db:seed
```

### 2. Start the Development Server
```bash
php artisan serve
```

### 3. Access Admin Panel
- **URL:** http://localhost:8000/admin/login
- **Email:** admin@jewishhouse.com
- **Password:** admin

---

## Admin Panel Structure

### Sidebar Navigation
```
📊 Dashboard          → Overview and statistics
├── Management
│   ├── 📅 Events     → Create and manage events
│   ├── ✓ Registrations → Manage registrations
│   └── 👥 Users      → User management
├── Analytics
│   └── 📈 Analytics  → Charts and reports
└── Settings
    └── ⚙️ Settings   → Portal configuration
```

### Dashboard Features
- **Statistics Cards:** Total Events, Registrations, Attendees, Pending Approvals
- **Upcoming Events Table:** Recent events list (placeholder)
- **Quick Actions:** Create event, manage users, view analytics, settings
- **System Status:** Database, Email Service, API status

---

## Key Sections

### 🔐 Authentication
- **Login Page:** Professional design with gradient background
- **Session Management:** Remember me functionality
- **Logout:** Secure session termination

### 📊 Dashboard
- Real-time statistics overview
- Quick access to all features
- System health indicators
- Recent activity feed (ready for integration)

### 📅 Events Management
- Create, edit, delete events
- Filter by category and status
- View registrations per event
- Manage event capacity and waitlists

### ✓ Registrations
- View all registrations
- Filter by event and status
- Track attendee confirmations
- Export registration data

### 👥 Users Management
- Create and manage admin accounts
- Set user roles and permissions
- Monitor user activity
- Deactivate/reactivate users

### 📈 Analytics & Reports
- Registration trends chart
- Event category breakdown
- Attendance analytics
- Performance metrics

### ⚙️ Settings
- General portal settings
- Email configuration (Gmail API)
- Security and password management
- Database backup settings

---

## Color Theme

### Palette
| Color | Hex | Usage |
|-------|-----|-------|
| Primary | #2563eb | Buttons, Links, Icons |
| Secondary | #1e40af | Gradients, Hover states |
| Success | #10b981 | Completed, Active status |
| Danger | #ef4444 | Errors, Delete actions |
| Warning | #f59e0b | Alerts, Pending status |
| Info | #0ea5e9 | Information badges |
| Light | #f8fafc | Backgrounds |
| Border | #e2e8f0 | Dividers, Borders |

### Gradients
- **Primary Gradient:** `#667eea` → `#764ba2` (Sidebar)
- **Button Gradient:** `#2563eb` → `#1e40af` (Buttons)

---

## User Roles

### Admin (is_admin = true)
- ✅ Access admin panel
- ✅ Manage all events
- ✅ Manage all registrations
- ✅ Manage users
- ✅ View analytics
- ✅ Configure settings
- ✅ Create announcements

### Regular User (is_admin = false)
- ✅ Register for events
- ✅ View personal registrations
- ✅ Update profile
- ❌ Cannot access admin panel

---

## Important URLs

```
Admin Login:      /admin/login
Admin Dashboard:  /admin/dashboard
Events:          /admin/events
Registrations:   /admin/registrations
Users:           /admin/users
Analytics:       /admin/analytics
Settings:        /admin/settings
```

---

## Database Fields

### Users Table
```sql
id              INT PRIMARY KEY
name            VARCHAR(255)
email           VARCHAR(255) UNIQUE
password        VARCHAR(255) HASHED
is_admin        BOOLEAN DEFAULT false
email_verified_at TIMESTAMP
remember_token  VARCHAR(100)
created_at      TIMESTAMP
updated_at      TIMESTAMP
```

---

## File Locations

```
app/
├── Http/
│   ├── Controllers/Admin/
│   │   ├── AuthController.php
│   │   └── DashboardController.php
│   └── Middleware/
│       └── IsAdmin.php
└── Models/
    └── User.php

resources/views/
└── admin/
    ├── login.blade.php
    ├── layouts/
    │   └── app.blade.php
    ├── dashboard.blade.php
    ├── events/
    │   └── index.blade.php
    ├── registrations/
    │   └── index.blade.php
    ├── users/
    │   └── index.blade.php
    ├── analytics/
    │   └── index.blade.php
    └── settings/
        └── index.blade.php

routes/
└── web.php

database/
├── migrations/
│   └── 2024_05_12_000000_add_is_admin_to_users_table.php
└── seeders/
    └── AdminSeeder.php
```

---

## Common Tasks

### Create New Admin User
```php
// Option 1: Via tinker
php artisan tinker
>>> User::create(['name' => 'New Admin', 'email' => 'admin2@example.com', 'password' => Hash::make('password'), 'is_admin' => true])

// Option 2: Via database
UPDATE users SET is_admin = 1 WHERE id = 2;
```

### Reset Admin Password
```php
php artisan tinker
>>> User::where('email', 'admin@jewishhouse.com')->first()->update(['password' => Hash::make('newpassword')])
```

### Check User Status
```php
php artisan tinker
>>> User::find(1)->is_admin
```

---

## Keyboard Shortcuts

| Shortcut | Action |
|----------|--------|
| `Ctrl/Cmd + /` | Show help (future feature) |
| `Ctrl/Cmd + K` | Command palette (future feature) |
| `ESC` | Close dropdowns |

---

## Browser DevTools

### Useful CSS Classes
```css
.sidebar           - Main sidebar
.header            - Top navigation bar
.main-content      - Main content area
.stat-card         - Statistics card
.card              - Generic card container
.btn-primary       - Primary button
.table             - Data table
```

### Console Commands
```javascript
// Check sidebar state
document.querySelector('.sidebar').classList.toggle('show')

// Clear session
sessionStorage.clear()

// Check local storage
localStorage
```

---

## Performance Tips

### Optimization
1. Use CDN for external libraries
2. Minify CSS and JavaScript in production
3. Enable GZIP compression
4. Use database indexes
5. Implement caching

### Monitoring
- Check server response times
- Monitor database queries
- Track user sessions
- Analyze error logs

---

## Support Contacts

- **Technical Support:** [Your support email]
- **Bug Reports:** [Your bug tracking system]
- **Feature Requests:** [Your request system]
- **Documentation:** [Your wiki/docs]

---

## Updates & Maintenance

### Regular Tasks
- ✅ Backup database daily
- ✅ Monitor server logs
- ✅ Update dependencies quarterly
- ✅ Review user activity
- ✅ Test disaster recovery

### Version Updates
```bash
# Update composer dependencies
composer update

# Update npm dependencies
npm update

# Run migrations
php artisan migrate
```

---

## Security Checklist

- ✅ Use strong passwords (12+ characters, mixed case, numbers, symbols)
- ✅ Keep software updated
- ✅ Regularly backup database
- ✅ Monitor admin activity
- ✅ Use HTTPS in production
- ✅ Enable session timeout
- ✅ Implement two-factor authentication (future)
- ✅ Log all admin actions (future)

---

**Last Updated:** May 12, 2026  
**Version:** 1.0.0  
**Status:** Production Ready
