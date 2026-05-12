# Admin Panel Setup Guide

## Overview
A professional admin panel has been created for the Jewish House Event & Community Engagement Portal with authentication, light theme design, and complete management features.

## Features Included

### Authentication
- ✅ Admin login page with professional design
- ✅ Session management with role-based access control
- ✅ Default admin credentials: `admin@jewishhouse.com` / `admin`
- ✅ Secure logout functionality

### Dashboard Components
- ✅ Responsive sidebar with navigation menu
- ✅ Header with user profile and dropdown menu
- ✅ Statistics cards showing key metrics
- ✅ Quick action buttons
- ✅ System status indicator
- ✅ Recent events table (placeholder)

### Admin Pages
1. **Dashboard** - Overview with statistics and quick actions
2. **Events Management** - Create, edit, delete events
3. **Registrations** - View and manage event registrations
4. **Users Management** - Manage portal users
5. **Analytics & Reports** - Charts and analytics dashboard
6. **Settings** - Portal configuration

### Design Features
- Light color theme with professional gradient accents
- Sidebar navigation with active state highlighting
- Responsive design for mobile, tablet, and desktop
- Smooth animations and transitions
- Interactive cards and buttons
- Toast notifications for user feedback

---

## Installation & Setup

### Step 1: Run Migrations
Add the `is_admin` column to the users table:

```bash
php artisan migrate
```

### Step 2: Run Database Seeder
Create the default admin user:

```bash
php artisan db:seed --class=AdminSeeder
```

Or seed all data (which includes the admin user):

```bash
php artisan db:seed
```

### Step 3: Clear Cache (Optional but Recommended)
```bash
php artisan config:cache
php artisan route:cache
```

---

## File Structure

### Created Files

#### Controllers
- `app/Http/Controllers/Admin/AuthController.php` - Login/logout logic
- `app/Http/Controllers/Admin/DashboardController.php` - Dashboard and menu pages

#### Middleware
- `app/Http/Middleware/IsAdmin.php` - Admin role verification

#### Views
- `resources/views/admin/login.blade.php` - Login page
- `resources/views/admin/layouts/app.blade.php` - Admin layout template
- `resources/views/admin/dashboard.blade.php` - Dashboard page
- `resources/views/admin/events/index.blade.php` - Events management
- `resources/views/admin/registrations/index.blade.php` - Registrations page
- `resources/views/admin/users/index.blade.php` - Users management
- `resources/views/admin/analytics/index.blade.php` - Analytics & reports
- `resources/views/admin/settings/index.blade.php` - Settings page

#### Database
- `database/migrations/2024_05_12_000000_add_is_admin_to_users_table.php` - Migration
- `database/seeders/AdminSeeder.php` - Admin seeder

#### Configuration
- `routes/web.php` - Updated with admin routes
- `bootstrap/app.php` - Middleware registration
- `app/Models/User.php` - Updated with is_admin field

---

## Routes

### Public Routes
```
GET  /                    - Welcome page
```

### Admin Routes (Protected)
```
GET  /admin/login         - Admin login form
POST /admin/login         - Process login
POST /admin/logout        - Logout

GET  /admin/dashboard     - Dashboard (requires auth & admin role)
GET  /admin/events        - Events management
GET  /admin/registrations - Registrations page
GET  /admin/users         - Users management
GET  /admin/analytics     - Analytics & reports
GET  /admin/settings      - Settings page
```

---

## Accessing the Admin Panel

### URL
```
http://localhost:8000/admin/login
```

### Default Login Credentials
- **Email:** `admin@jewishhouse.com`
- **Password:** `admin`

### After Login
You'll be redirected to the dashboard at:
```
http://localhost:8000/admin/dashboard
```

---

## Customization Guide

### Changing the Color Theme
Edit `resources/views/admin/layouts/app.blade.php` and update the CSS variables:

```css
:root {
    --primary-color: #2563eb;      /* Main blue */
    --secondary-color: #1e40af;    /* Darker blue */
    --success-color: #10b981;      /* Green */
    --danger-color: #ef4444;       /* Red */
    --warning-color: #f59e0b;      /* Orange */
    --info-color: #0ea5e9;         /* Light blue */
    --light-bg: #f8fafc;           /* Light background */
    --light-border: #e2e8f0;       /* Light border */
}
```

### Adding New Menu Items
Edit the sidebar menu in `resources/views/admin/layouts/app.blade.php`:

```blade
<li>
    <a href="{{ route('admin.new-page') }}" class="@if(Route::currentRouteName() == 'admin.new-page') active @endif">
        <i class="fas fa-icon-name"></i>
        <span>New Page</span>
    </a>
</li>
```

Then add the route in `routes/web.php`:

```php
Route::get('/new-page', [DashboardController::class, 'newPage'])->name('new-page');
```

### Adding New Controller Methods
Create methods in `app/Http/Controllers/Admin/DashboardController.php`:

```php
public function newPage()
{
    return view('admin.new-page.index');
}
```

---

## Security Considerations

### Admin Middleware
The `IsAdmin` middleware ensures:
1. User is authenticated
2. User has `is_admin` role set to true
3. Redirects to login if conditions are not met

### Password Hashing
Passwords are automatically hashed using Laravel's password hashing. Never store plain text passwords.

### Session Management
- Sessions are configured in `config/session.php`
- Remember me functionality is available on login
- Session expires after inactivity

---

## Testing the Admin Panel

### Test Login
1. Navigate to `http://localhost:8000/admin/login`
2. Enter credentials:
   - Email: `admin@jewishhouse.com`
   - Password: `admin`
3. Click "Sign In"
4. You should see the dashboard

### Test Logout
1. Click on the user dropdown in the top right
2. Click "Logout"
3. You'll be redirected to the login page

### Test Authorization
1. Try accessing `/admin/dashboard` without logging in
2. You should be redirected to the login page
3. Only users with `is_admin = true` can access admin pages

---

## Browser Support

The admin panel supports:
- ✅ Chrome 100+
- ✅ Firefox 100+
- ✅ Safari 15+
- ✅ Edge 100+
- ✅ Mobile browsers (iOS Safari, Chrome Mobile)

---

## Responsive Design

The admin panel is fully responsive:
- **Desktop (1024px+):** Full sidebar + full content
- **Tablet (768px-1023px):** Responsive cards and tables
- **Mobile (< 768px):** Collapsible sidebar, mobile-optimized layout

---

## Dependencies

### Frontend Libraries
- Bootstrap 5.3.0
- Font Awesome 6.4.0
- Chart.js 4.4.0

### Backend
- Laravel 11.x
- PHP 8.2+
- MySQL 8.0+

---

## Troubleshooting

### Issue: "Unauthorized access" error
**Solution:** Make sure the user has `is_admin = 1` in the database:
```sql
UPDATE users SET is_admin = 1 WHERE email = 'admin@jewishhouse.com';
```

### Issue: Middleware not working
**Solution:** Clear the route cache:
```bash
php artisan route:clear
php artisan config:clear
```

### Issue: Login keeps redirecting
**Solution:** Check your `.env` file is correctly configured and the database connection is working:
```bash
php artisan migrate
php artisan db:seed
```

### Issue: CSS/JS not loading
**Solution:** Run Vite build:
```bash
npm run build
```

For development:
```bash
npm run dev
```

---

## Next Steps

1. **Create Event Models** - Develop event CRUD functionality
2. **Build Registration System** - Create registration forms and management
3. **Implement Email Notifications** - Set up Gmail API integration
4. **Add Analytics** - Connect real data to charts
5. **Create Salesforce Integration** - Build CRM integration
6. **Deployment** - Deploy to production VPS

---

## Support & Documentation

For more information:
- [Laravel Documentation](https://laravel.com/docs)
- [Bootstrap Documentation](https://getbootstrap.com/docs)
- [Font Awesome Icons](https://fontawesome.com/icons)
- [Chart.js Documentation](https://www.chartjs.org/docs/latest/)

---

## Version History

- **v1.0.0** (May 2026) - Initial admin panel release
  - Authentication system
  - Dashboard with statistics
  - Event management interface
  - User management
  - Analytics dashboard
  - Settings page
  - Light color theme
  - Responsive design

---

**Created for:** Jewish House Event & Community Engagement Portal  
**Project ID:** Project-2026S1_32  
**Last Updated:** May 12, 2026
