# Email Reminder and Feedback Collection Implementation

## Overview
A complete email notification system has been implemented for the JHPortal event management platform, featuring:
- Event reminder emails (24 hours before event start)
- Post-event feedback collection emails
- Gmail SMTP configuration interface
- Admin panel for email settings management
- Artisan commands for automated email sending

## Database Migrations

### 1. Email Reminders Table
**File**: [`database/migrations/2026_05_26_104000_create_email_reminders_table.php`](database/migrations/2026_05_26_104000_create_email_reminders_table.php)

Tracks all email reminders sent to users:
- `event_id` - Foreign key to events
- `user_id` - Foreign key to users
- `type` - Enum: 'event_reminder' or 'feedback_request'
- `scheduled_at` - When email was scheduled
- `sent_at` - When email was actually sent
- `status` - Enum: 'pending', 'sent', 'failed'
- `error_message` - Error details if failed

### 2. Email Configurations Table
**File**: [`database/migrations/2026_05_26_105000_create_email_configurations_table.php`](database/migrations/2026_05_26_105000_create_email_configurations_table.php)

Stores Gmail SMTP configuration:
- `mailer` - Mail driver type (smtp, log)
- `host` - SMTP host (smtp.gmail.com)
- `port` - SMTP port (587 for TLS, 465 for SSL)
- `username` - Gmail email address
- `password` - Gmail app password
- `encryption` - TLS or SSL
- `from_address` - Sender email
- `from_name` - Sender name
- `is_active` - Whether this config is active

## Models

### EmailReminder Model
**File**: [`app/Models/EmailReminder.php`](app/Models/EmailReminder.php)

Features:
- Relationships to Event and User models
- `markAsSent()` - Mark reminder as successfully sent
- `markAsFailed(string $error)` - Mark reminder as failed with error message
- `scopePending()` - Query scope to get pending reminders ready to send

### EmailConfiguration Model
**File**: [`app/Models/EmailConfiguration.php`](app/Models/EmailConfiguration.php)

Features:
- `getActive()` - Static method to get active configuration
- `applyToConfig()` - Apply configuration to Laravel mail config at runtime

## Artisan Commands

### Send Event Reminders Command
**File**: [`app/Console/Commands/SendEventReminders.php`](app/Console/Commands/SendEventReminders.php)

**Usage**: `php artisan email:send-reminders`

Functionality:
- Finds events starting in approximately 24 hours
- Gets all registered users for those events
- Sends reminder email to each user
- Tracks sent reminders in email_reminders table
- Prevents duplicate reminders

### Send Feedback Emails Command
**File**: [`app/Console/Commands/SendFeedbackEmails.php`](app/Console/Commands/SendFeedbackEmails.php)

**Usage**: `php artisan email:send-feedback-requests`

Functionality:
- Finds events that ended in the last 24 hours
- Gets registered users who attended
- Checks if feedback already submitted
- Sends feedback request email
- Tracks sent feedback requests in email_reminders table

## Admin Controllers

### Email Settings Controller
**File**: [`app/Http/Controllers/Admin/EmailSettingsController.php`](app/Http/Controllers/Admin/EmailSettingsController.php)

Methods:
- `index()` - Display email configuration form
- `store(Request $request)` - Save email configuration
- `testEmail(Request $request)` - Send test email to verify configuration

## Admin Views

### Email Settings Configuration Page
**File**: [`resources/views/admin/email-settings/index.blade.php`](resources/views/admin/email-settings/index.blade.php)

Features:
- SMTP configuration form with fields for:
  - Mailer type (SMTP or Log)
  - SMTP host
  - SMTP port
  - Encryption type (TLS/SSL)
  - Gmail email address
  - Gmail app password
  - From address and name
- Active/inactive toggle
- Gmail setup guide with step-by-step instructions
- Test email functionality
- Form validation with error messages

## Routes

**File**: [`routes/web.php`](routes/web.php)

Added routes (admin protected):
```
GET    /admin/email-settings ..................... admin.email-settings.index
POST   /admin/email-settings ..................... admin.email-settings.store
POST   /admin/email-settings/test ............... admin.email-settings.test
```

## Email Notification Service

**File**: [`app/Services/EmailNotificationService.php`](app/Services/EmailNotificationService.php)

Existing methods used:
- `sendEventReminder(User $user, Event $event)` - Send reminder email
- `sendFeedbackRequest(User $user, Event $event)` - Send feedback request email

These methods:
- Create EmailLog entries for tracking
- Send emails using Laravel Mail facade
- Handle exceptions and log failures

## Setup Instructions

### 1. Gmail Configuration
1. Enable 2-Step Verification on your Google Account
2. Go to [App Passwords](https://myaccount.google.com/apppasswords)
3. Select "Mail" and "Windows Computer"
4. Copy the generated 16-character password
5. Do NOT use your regular Gmail password

### 2. Admin Panel Configuration
1. Navigate to Admin → Email Settings
2. Fill in the configuration:
   - **Host**: smtp.gmail.com
   - **Port**: 587
   - **Encryption**: TLS
   - **Username**: your-email@gmail.com
   - **Password**: Your 16-character app password
   - **From Address**: noreply@yourdomain.com
   - **From Name**: JHPortal
3. Check "Activate this configuration"
4. Click "Save Configuration"
5. Test with "Send Test Email" button

### 3. Schedule Commands
Add to your server's cron job or task scheduler:

```bash
# Send event reminders every hour
0 * * * * cd /path/to/jhportal && php artisan email:send-reminders

# Send feedback requests every 6 hours
0 */6 * * * cd /path/to/jhportal && php artisan email:send-feedback-requests
```

Or use Laravel's task scheduler in [`app/Console/Kernel.php`](app/Console/Kernel.php):

```php
$schedule->command('email:send-reminders')->hourly();
$schedule->command('email:send-feedback-requests')->everyFourHours();
```

## Email Flow

### Event Reminder Flow
1. Event is created and published
2. Users register for event
3. 24 hours before event start:
   - `email:send-reminders` command runs
   - Finds registered users
   - Sends reminder email via Gmail SMTP
   - Creates EmailReminder record with status 'sent'
   - User receives email with event details

### Feedback Collection Flow
1. Event ends
2. Within 24 hours after event end:
   - `email:send-feedback-requests` command runs
   - Finds registered users who attended
   - Checks if feedback already submitted
   - Sends feedback request email
   - Creates EmailReminder record with status 'sent'
   - User receives email with feedback link

## Database Schema

### email_reminders table
```sql
CREATE TABLE email_reminders (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  event_id BIGINT NOT NULL,
  user_id BIGINT NOT NULL,
  type ENUM('event_reminder', 'feedback_request'),
  scheduled_at TIMESTAMP,
  sent_at TIMESTAMP,
  status ENUM('pending', 'sent', 'failed'),
  error_message TEXT,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  UNIQUE(event_id, user_id, type),
  INDEX(status, scheduled_at),
  FOREIGN KEY(event_id) REFERENCES events(id),
  FOREIGN KEY(user_id) REFERENCES users(id)
);
```

### email_configurations table
```sql
CREATE TABLE email_configurations (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  mailer VARCHAR(255),
  host VARCHAR(255),
  port INT,
  username VARCHAR(255),
  password VARCHAR(255),
  encryption VARCHAR(255),
  from_address VARCHAR(255),
  from_name VARCHAR(255),
  is_active BOOLEAN,
  notes TEXT,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

## Files Created

1. [`database/migrations/2026_05_26_104000_create_email_reminders_table.php`](database/migrations/2026_05_26_104000_create_email_reminders_table.php)
2. [`database/migrations/2026_05_26_105000_create_email_configurations_table.php`](database/migrations/2026_05_26_105000_create_email_configurations_table.php)
3. [`app/Models/EmailReminder.php`](app/Models/EmailReminder.php)
4. [`app/Models/EmailConfiguration.php`](app/Models/EmailConfiguration.php)
5. [`app/Console/Commands/SendEventReminders.php`](app/Console/Commands/SendEventReminders.php)
6. [`app/Console/Commands/SendFeedbackEmails.php`](app/Console/Commands/SendFeedbackEmails.php)
7. [`app/Http/Controllers/Admin/EmailSettingsController.php`](app/Http/Controllers/Admin/EmailSettingsController.php)
8. [`resources/views/admin/email-settings/index.blade.php`](resources/views/admin/email-settings/index.blade.php)

## Files Modified

1. [`routes/web.php`](routes/web.php) - Added email settings routes

## Testing

### Manual Testing
1. Configure Gmail SMTP in admin panel
2. Send test email to verify configuration
3. Create an event with start date 24 hours from now
4. Register a user for the event
5. Run: `php artisan email:send-reminders`
6. Check user's email for reminder

### Command Testing
```bash
# Test reminder command
php artisan email:send-reminders

# Test feedback command
php artisan email:send-feedback-requests

# Check email logs
php artisan tinker
>>> App\Models\EmailReminder::all();
```

## Troubleshooting

### Emails not sending
1. Verify Gmail SMTP configuration is active
2. Check that app password is correct (not regular Gmail password)
3. Ensure 2-Step Verification is enabled on Gmail account
4. Check email_reminders table for failed status and error messages
5. Review Laravel logs in `storage/logs/`

### Configuration not applying
1. Clear config cache: `php artisan config:clear`
2. Verify EmailConfiguration record exists and is_active = true
3. Check that from_address and from_name are set

### Commands not running
1. Verify cron job is configured correctly
2. Test command manually: `php artisan email:send-reminders`
3. Check Laravel logs for command execution errors
4. Ensure database migrations have run: `php artisan migrate`

## Future Enhancements

- Email templates with HTML formatting
- Customizable email content per event
- Email delivery tracking and analytics
- Retry logic for failed emails
- Bulk email sending optimization
- Email preview functionality in admin panel
- Unsubscribe functionality
- Email scheduling UI in event creation form
