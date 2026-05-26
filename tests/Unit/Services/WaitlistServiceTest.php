<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use App\Models\Waitlist;
use App\Models\EventRegistration;
use App\Services\WaitlistService;
use App\Services\EmailNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WaitlistServiceTest extends TestCase
{
    use RefreshDatabase;

    protected WaitlistService $waitlistService;
    protected EmailNotificationService $emailService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->emailService = $this->createMock(EmailNotificationService::class);
        $this->waitlistService = new WaitlistService($this->emailService);
    }

    /** @test */
    public function it_can_add_user_to_waitlist()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['capacity' => 1]);

        $waitlist = $this->waitlistService->addToWaitlist($user, $event);

        $this->assertNotNull($waitlist);
        $this->assertEquals($user->id, $waitlist->user_id);
        $this->assertEquals($event->id, $waitlist->event_id);
        $this->assertEquals('pending', $waitlist->status);
    }

    /** @test */
    public function it_prevents_duplicate_waitlist_entries()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();

        $this->waitlistService->addToWaitlist($user, $event);
        $result = $this->waitlistService->addToWaitlist($user, $event);

        $this->assertNull($result);
        $this->assertEquals(1, Waitlist::count());
    }

    /** @test */
    public function it_prevents_registered_users_from_joining_waitlist()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();

        EventRegistration::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 'registered',
        ]);

        $result = $this->waitlistService->addToWaitlist($user, $event);

        $this->assertNull($result);
    }

    /** @test */
    public function it_can_remove_user_from_waitlist()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();

        $this->waitlistService->addToWaitlist($user, $event);
        $removed = $this->waitlistService->removeFromWaitlist($user, $event);

        $this->assertTrue($removed);
        $waitlist = Waitlist::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();
        $this->assertEquals('cancelled', $waitlist->status);
    }

    /** @test */
    public function it_can_notify_waitlist_member()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $waitlist = Waitlist::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 'pending',
        ]);

        $this->emailService->expects($this->once())
            ->method('sendWaitlistNotification');

        $result = $this->waitlistService->notifyWaitlistMember($waitlist);

        $this->assertTrue($result);
        $this->assertEquals('notified', $waitlist->fresh()->status);
    }

    /** @test */
    public function it_can_confirm_waitlist_member()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['capacity' => 1]);
        $waitlist = Waitlist::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 'notified',
            'notified_at' => now(),
        ]);

        $result = $this->waitlistService->confirmWaitlistMember($waitlist);

        $this->assertTrue($result);
        $this->assertEquals('confirmed', $waitlist->fresh()->status);
        $this->assertTrue(
            EventRegistration::where('user_id', $user->id)
                ->where('event_id', $event->id)
                ->exists()
        );
    }

    /** @test */
    public function it_prevents_confirmation_when_event_is_full()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['capacity' => 1]);

        EventRegistration::create([
            'user_id' => User::factory()->create()->id,
            'event_id' => $event->id,
            'status' => 'registered',
        ]);

        $waitlist = Waitlist::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 'notified',
        ]);

        $result = $this->waitlistService->confirmWaitlistMember($waitlist);

        $this->assertFalse($result);
    }

    /** @test */
    public function it_can_promote_next_waitlist_member()
    {
        $event = Event::factory()->create(['capacity' => 1]);
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Waitlist::create([
            'user_id' => $user1->id,
            'event_id' => $event->id,
            'status' => 'pending',
            'position' => 1,
        ]);

        Waitlist::create([
            'user_id' => $user2->id,
            'event_id' => $event->id,
            'status' => 'pending',
            'position' => 2,
        ]);

        $this->emailService->expects($this->once())
            ->method('sendWaitlistNotification');

        $promoted = $this->waitlistService->promoteNextWaitlistMember($event);

        $this->assertNotNull($promoted);
        $this->assertEquals($user1->id, $promoted->user_id);
        $this->assertEquals('notified', $promoted->fresh()->status);
    }

    /** @test */
    public function it_can_reorder_waitlist_positions()
    {
        $event = Event::factory()->create();
        $users = User::factory()->count(3)->create();

        foreach ($users as $index => $user) {
            Waitlist::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'status' => 'pending',
                'position' => $index + 1,
            ]);
        }

        $this->waitlistService->reorderWaitlist($event);

        $waitlist = Waitlist::where('event_id', $event->id)
            ->orderBy('position')
            ->get();

        foreach ($waitlist as $index => $entry) {
            $this->assertEquals($index + 1, $entry->position);
        }
    }

    /** @test */
    public function it_can_get_waitlist_statistics()
    {
        $event = Event::factory()->create();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        Waitlist::create([
            'user_id' => $user1->id,
            'event_id' => $event->id,
            'status' => 'pending',
        ]);

        Waitlist::create([
            'user_id' => $user2->id,
            'event_id' => $event->id,
            'status' => 'notified',
            'notified_at' => now(),
        ]);

        Waitlist::create([
            'user_id' => $user3->id,
            'event_id' => $event->id,
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        $stats = $this->waitlistService->getWaitlistStats($event);

        $this->assertEquals(3, $stats['total']);
        $this->assertEquals(1, $stats['pending']);
        $this->assertEquals(1, $stats['notified']);
        $this->assertEquals(1, $stats['confirmed']);
    }

    /** @test */
    public function it_can_get_user_waitlist_position()
    {
        $event = Event::factory()->create();
        $user = User::factory()->create();

        Waitlist::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 'pending',
            'position' => 1,
        ]);

        $position = $this->waitlistService->getUserWaitlistPosition($user, $event);

        $this->assertEquals(1, $position);
    }

    /** @test */
    public function it_returns_null_for_non_waitlisted_user()
    {
        $event = Event::factory()->create();
        $user = User::factory()->create();

        $position = $this->waitlistService->getUserWaitlistPosition($user, $event);

        $this->assertNull($position);
    }

    /** @test */
    public function it_can_check_if_user_is_on_waitlist()
    {
        $event = Event::factory()->create();
        $user = User::factory()->create();

        $this->assertFalse($this->waitlistService->isUserOnWaitlist($user, $event));

        Waitlist::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 'pending',
        ]);

        $this->assertTrue($this->waitlistService->isUserOnWaitlist($user, $event));
    }

    /** @test */
    public function it_can_expire_old_notifications()
    {
        $event = Event::factory()->create();
        $user = User::factory()->create();

        Waitlist::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 'notified',
            'notified_at' => now()->subDays(8),
        ]);

        $expired = $this->waitlistService->expireOldNotifications(7);

        $this->assertEquals(1, $expired);
        $this->assertEquals('expired', Waitlist::first()->status);
    }

    /** @test */
    public function it_can_bulk_notify_waitlist_members()
    {
        $event = Event::factory()->create();
        $users = User::factory()->count(3)->create();

        foreach ($users as $user) {
            Waitlist::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'status' => 'pending',
            ]);
        }

        $this->emailService->expects($this->exactly(3))
            ->method('sendWaitlistNotification');

        $notified = $this->waitlistService->bulkNotifyWaitlist($event);

        $this->assertEquals(3, $notified);
    }
}
