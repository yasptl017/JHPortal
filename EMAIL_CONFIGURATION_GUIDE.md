# Email Configuration Guide - Which Email is Used?

## Email Address Priority (Fallback Hierarchy)

The system uses the following priority order to determine which email address sends reminders and feedback emails:

### Priority 1: Active EmailConfiguration (Admin Panel)
**File**: [`app/Models/EmailConfiguration.php`](app/Models/EmailConfiguration.php:28-48)

If an admin has configured email settings in the admin panel AND activated the configuration:
- **From Address**: Uses `from_address` from database
- **From Name**: Uses `from_name` from database
- **SMTP Details**: Uses configured host, port, username, password, encryption

**How it works**:
```php
$config = EmailConfiguration::getActive(); // Gets active config from database
if ($config) {
    $config->applyToConfig(); // Applies to Laravel mail config at runtime
}
```

### Priority 2: Environment Variables (.env file)
**File**: [`.env.example`](.env.example:50-57)

If NO active EmailConfiguration exists, Laravel uses environment variables:
```
MAIL_MAILER=log
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="JHPortal"
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
```

### Priority 3: Default Config
**File**: [`config/mail.php`](config/mail.php:17-50)

If environment variables are not set, Laravel uses config defaults:
- Default mailer: `log` (logs emails instead of sending)
- Default from address: `hello@example.com`
- Default from name: `JHPortal`

## Current Setup Status

### ✅ What's Configured
- Email configuration table created in database
- Admin panel interface available at `/admin/email-settings`
- Artisan commands ready: `email:send-reminders` and `email:send-feedback-requests`
- Error handling implemented with logging

### ⚠️ What Needs Configuration
**Admin must configure email settings in the admin panel**:

1. Navigate to: **Admin → Email Settings**
2. Fill in Gmail SMTP details:
   - **Host**: smtp.gmail.com
   - **Port**: 587
   - **Encryption**: TLS
   - **Username**: your-email@gmail.com
   - **App Password**: 16-character app password (NOT regular Gmail password)
   - **From Address**: noreply@yourdomain.com (or any valid email)
   - **From Name**: JHPortal (or your organization name)
3. Click "Activate this configuration"
4. Test with "Send Test Email" button

## Email Flow for Reminders and Feedback

### Event Reminder Email (24 hours before event)
1. **Command runs**: `php artisan email:send-reminders`
2. **Gets active config**: Checks if EmailConfiguration is active
3. **Applies config**: Sets up SMTP with configured credentials
4. **Sends email**: Uses `from_address` and `from_name` from configuration
5. **Logs result**: Records in `email_reminders` table

### Feedback Request Email (after event ends)
1. **Command runs**: `php artisan email:send-feedback-requests`
2. **Gets active config**: Checks if EmailConfiguration is active
3. **Applies config**: Sets up SMTP with configured credentials
4. **Sends email**: Uses `from_address` and `from_name` from configuration
5. **Logs result**: Records in `email_reminders` table

## Code References

### Where Email Configuration is Applied
**File**: [`app/Console/Commands/SendEventReminders.php`](app/Console/Commands/SendEventReminders.php:15-18)
```php
$config = EmailConfiguration::getActive();
if ($config) {
    $config->applyToConfig();
}
```

**File**: [`app/Console/Commands/SendFeedbackEmails.php`](app/Console/Commands/SendFeedbackEmails.php:15-18)
```php
$config = EmailConfiguration::getActive();
if ($config) {
    $config->applyToConfig();
}
```

### Where Emails are Sent
**File**: [`app/Services/EmailNotificationService.php`](app/Services/EmailNotificationService.php:49-77)
- `sendEventReminder()` method
- `sendFeedbackRequest()` method

Both use:
```php
Mail::raw($body, function ($message) use ($user, $subject) {
    $message->to($user->email)
        ->subject($subject)
        ->from(config('mail.from.address'), config('mail.from.name'));
});
```

## Testing Email Configuration

### Step 1: Configure in Admin Panel
1. Go to `/admin/email-settings`
2. Enter Gmail SMTP credentials
3. Activate configuration

### Step 2: Send Test Email
1. Enter test email address
2. Click "Send Test Email"
3. Check if email arrives

### Step 3: Check Logs
If test email fails:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Look for error messages
3. Verify Gmail app password is correct

### Step 4: Run Commands Manually
```bash
# Test reminder command
php artisan email:send-reminders

# Test feedback command
php artisan email:send-feedback-requests

# Check email_reminders table
php artisan tinker
>>> App\Models\EmailReminder::all();
```

## Troubleshooting

### Issue: "No email configuration found"
**Solution**: Configure email settings in admin panel and activate

### Issue: "Failed to send test email"
**Solution**: 
- Verify Gmail app password (not regular password)
- Ensure 2-Step Verification is enabled on Gmail
- Check that from_address is valid

### Issue: Emails not sending automatically
**Solution**:
- Verify cron job is configured
- Check Laravel logs for errors
- Ensure EmailConfiguration is active
- Run commands manually to test

## Summary

**Which email is used?**
- If admin configured: Uses `from_address` from admin panel
- If not configured: Uses `MAIL_FROM_ADDRESS` from .env file
- If neither: Uses default `hello@example.com`

**Current status**: Admin panel is ready, but admin must configure Gmail SMTP credentials to enable email sending.
