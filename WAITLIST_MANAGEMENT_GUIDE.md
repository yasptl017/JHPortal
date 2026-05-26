# Waitlist Management Feature Guide

## Overview

The Waitlist Management feature allows users to join a waitlist when events are full, and administrators to manage waitlist entries with automated promotion capabilities.

## Features

### User Features
- **Join Waitlist**: Users can join the waitlist when an event is at capacity
- **View Waitlist**: Users can see all their waitlist entries with status and position
- **Leave Waitlist**: Users can remove themselves from a waitlist
- **Confirm Spot**: When notified, users have 7 days to confirm their spot
- **Position Tracking**: Users can see their position in the waitlist queue

### Admin Features
- **Waitlist Management**: View and manage all waitlist entries per event
- **Bulk Notifications**: Notify multiple waitlist members at once
- **Bulk Confirmations**: Confirm multiple members and register them
- **Automatic Promotion**: Automatically promote next member when spot opens
- **Export to CSV**: Export waitlist data for reporting
- **Statistics**: View waitlist statistics (pending, notified, confirmed, expired)
- **Position Management**: Automatic position reordering

## Database Schema

### Waitlist Table
```sql
CREATE TABLE waitlist (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    event_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,
    status ENUM('pending', 'notified', 'confirmed', 'cancelled', 'expired') DEFAULT 'pending',
    position INT,
    notified_at TIMESTAMP NULL,
    confirmed_at TIMESTAMP NULL,
    expired_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    UNIQUE KEY unique_event_user (event_id, user_id),
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_event_id (event_id),
    INDEX idx_user_id (user_id),
    INDEX idx_status (status)
);
```

## Models

### [`Waitlist`](app/Models/Waitlist.php)
Main model for waitlist entries with scopes and helper methods:
- `pending()` - Get pending entries
- `notified()` - Get notified entries
- `confirmed()` - Get confirmed entries
- `active()` - Get active entries (pending or notified)
- `forEvent()` - Filter by event
- `ordered()` - Order by position and creation date
- `markAsNotified()` - Mark as notified
- `markAsConfirmed()` - Mark as confirmed
- `markAsCancelled()` - Mark as cancelled
- `markAsExpired()` - Mark as expired
- `getPosition()` - Get current position
- `updatePosition()` - Update position in queue
- `getDaysUntilExpiration()` - Get days until expiration
- `isExpired()` - Check if expired

### [`Event`](app/Models/Event.php)
Enhanced with waitlist relationships:
- `waitlist()` - Get all waitlist entries
- `waitlistUsers()` - Get users on waitlist

## Services

### [`WaitlistService`](app/Services/WaitlistService.php)
Core business logic for waitlist operations:

**Key Methods:**
- `addToWaitlist(User, Event)` - Add user to waitlist
- `removeFromWaitlist(User, Event)` - Remove user from waitlist
- `notifyWaitlistMember(Waitlist)` - Send notification email
- `confirmWaitlistMember(Waitlist)` - Confirm and register user
- `promoteNextWaitlistMember(Event)` - Promote next member
- `promoteMultipleWaitlistMembers(Event, count)` - Promote multiple members
- `reorderWaitlist(Event)` - Reorder positions
- `expireOldNotifications(days)` - Expire old notifications
- `getWaitlistStats(Event)` - Get statistics
- `getUserWaitlistPosition(User, Event)` - Get user's position
- `isUserOnWaitlist(User, Event)` - Check if on waitlist
- `bulkNotifyWaitlist(Event, userIds)` - Bulk notify
- `bulkConfirmWaitlist(Event, userIds)` - Bulk confirm
- `clearExpiredEntries()` - Clean up expired entries

## Controllers

### Frontend [`WaitlistController`](app/Http/Controllers/Frontend/WaitlistController.php)
User-facing waitlist operations:
- `index()` - View user's waitlist entries
- `join(Event)` - Join event waitlist
- `leave(Event)` - Leave waitlist
- `position(Event)` - Get waitlist position (JSON)
- `details(Event)` - Get waitlist details (JSON)
- `confirm(Event)` - Confirm spot when notified

### Admin [`WaitlistController`](app/Http/Controllers/Admin/WaitlistController.php)
Admin management operations:
- `index()` - View waitlist management page
- `notify(Waitlist)` - Notify single member
- `confirm(Waitlist)` - Confirm single member
- `cancel(Waitlist)` - Cancel entry
- `destroy(Waitlist)` - Delete entry
- `bulkNotify()` - Bulk notify members
- `bulkConfirm()` - Bulk confirm members
- `promote()` - Promote next members
- `export()` - Export to CSV
- `stats()` - Get statistics (JSON)

## Routes

### Frontend Routes
```php
// Waitlist (authenticated users)
POST   /events/{event}/waitlist              → join()
POST   /events/{event}/waitlist/leave        → leave()
POST   /events/{event}/waitlist/confirm      → confirm()
GET    /waitlist                             → index()
GET    /events/{event}/waitlist/position     → position()
GET    /events/{event}/waitlist/details      → details()
```

### Admin Routes
```php
// Waitlist Management
GET    /admin/waitlist                       → index()
POST   /admin/waitlist/{waitlist}/notify     → notify()
POST   /admin/waitlist/{waitlist}/confirm    → confirm()
POST   /admin/waitlist/{waitlist}/cancel     → cancel()
DELETE /admin/waitlist/{waitlist}            → destroy()
POST   /admin/waitlist/bulk-notify           → bulkNotify()
POST   /admin/waitlist/bulk-confirm          → bulkConfirm()
POST   /admin/waitlist/promote               → promote()
GET    /admin/waitlist/export                → export()
GET    /admin/waitlist/stats                 → stats()
```

## Views

### Admin Views
- [`resources/views/admin/waitlist/index.blade.php`](resources/views/admin/waitlist/index.blade.php)
  - Event selection dropdown
  - Statistics cards (total, pending, notified, confirmed, active, expired)
  - Bulk action buttons
  - Waitlist table with member details
  - Export and action buttons

### Frontend Views
- [`resources/views/frontend/waitlist/index.blade.php`](resources/views/frontend/waitlist/index.blade.php)
  - User's waitlist entries
  - Status badges and information
  - Position display
  - Days until expiration countdown
  - Action buttons (view event, confirm spot, leave waitlist)
  - Empty state when no waitlist entries

## Workflow

### User Joining Waitlist
1. User views full event
2. User clicks "Join Waitlist"
3. System checks:
   - User not already registered
   - User not already on waitlist
   - Event is full
   - Waitlist is enabled
4. User added to waitlist with `pending` status
5. Position assigned based on join order

### Admin Notifying Member
1. Admin selects event
2. Admin clicks "Notify" on pending member
3. System:
   - Updates status to `notified`
   - Records `notified_at` timestamp
   - Sends email notification
   - Sets 7-day expiration

### User Confirming Spot
1. User receives notification email
2. User clicks "Confirm Spot" link
3. System:
   - Checks if event still has capacity
   - Creates event registration
   - Updates waitlist status to `confirmed`
   - Reorders remaining waitlist

### Automatic Promotion
1. User cancels registration
2. System triggers automatic promotion
3. Next pending member is notified
4. Process repeats

### Expiration
1. Member notified but doesn't confirm within 7 days
2. Status automatically changes to `expired`
3. Member can be removed from waitlist

## Email Notifications

### Waitlist Notification Email
Sent when member is notified about available spot:
- Event title and details
- Confirmation deadline (7 days)
- Link to confirm spot
- Link to view event

## Statistics

Admin can view real-time statistics:
- **Total**: All waitlist entries
- **Pending**: Waiting for notification
- **Notified**: Notified but not confirmed
- **Confirmed**: Registered for event
- **Cancelled**: User cancelled
- **Expired**: Notification expired
- **Active**: Pending + Notified

## CSV Export

Export waitlist data with columns:
- Position
- Name
- Email
- Status
- Joined Date
- Notified Date
- Confirmed Date

## Configuration

### Event Settings
Enable waitlist per event:
- `waitlist_enabled` boolean field on events table
- Set during event creation/editing

### Notification Settings
- Expiration period: 7 days (configurable in WaitlistService)
- Email notifications via EmailNotificationService

## Best Practices

1. **Enable Waitlist**: Always enable for events with limited capacity
2. **Monitor Waitlist**: Check admin dashboard regularly
3. **Promote Proactively**: Use bulk promote when spots open
4. **Clean Up**: Periodically clear expired entries
5. **Communicate**: Use custom waitlist messages in event settings

## Troubleshooting

### User can't join waitlist
- Check if event is actually full
- Verify waitlist is enabled for event
- Check if user already registered or on waitlist

### Notifications not sending
- Verify email configuration in `.env`
- Check email logs in admin panel
- Ensure user has valid email address

### Position not updating
- Run `php artisan migrate` to ensure schema is current
- Check database for position values
- Manually trigger reorder via admin panel

## Future Enhancements

- SMS notifications
- Waitlist priority levels
- Automatic confirmation after X days
- Waitlist analytics and reporting
- Integration with calendar systems
- Waitlist transfer between events
