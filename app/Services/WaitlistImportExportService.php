<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Waitlist;
use App\Models\User;
use Illuminate\Support\Collection;
use League\Csv\Reader;
use League\Csv\Writer;

class WaitlistImportExportService
{
    public function exportToCSV(Event $event): string
    {
        $waitlist = Waitlist::where('event_id', $event->id)
            ->with('user')
            ->orderBy('position')
            ->get();

        $csv = Writer::createFromString('');
        $csv->insertOne(['Position', 'Name', 'Email', 'Status', 'Joined Date', 'Notified Date', 'Confirmed Date']);

        foreach ($waitlist as $entry) {
            $csv->insertOne([
                $entry->position ?? 0,
                $entry->user->name,
                $entry->user->email,
                ucfirst($entry->status),
                $entry->created_at->format('Y-m-d H:i'),
                $entry->notified_at?->format('Y-m-d H:i') ?? '-',
                $entry->confirmed_at?->format('Y-m-d H:i') ?? '-',
            ]);
        }

        return $csv->toString();
    }

    public function importFromCSV(Event $event, string $csvContent): array
    {
        $csv = Reader::createFromString($csvContent);
        $csv->setHeaderOffset(0);

        $imported = 0;
        $errors = [];

        foreach ($csv->getRecords() as $index => $record) {
            try {
                $email = $record['Email'] ?? null;
                $name = $record['Name'] ?? null;

                if (!$email || !$name) {
                    $errors[] = "Row " . ($index + 2) . ": Missing email or name";
                    continue;
                }

                $user = User::where('email', $email)->first();
                if (!$user) {
                    $user = User::create([
                        'name' => $name,
                        'email' => $email,
                        'password' => bcrypt('password'),
                    ]);
                }

                $existing = Waitlist::where('event_id', $event->id)
                    ->where('user_id', $user->id)
                    ->first();

                if (!$existing) {
                    Waitlist::create([
                        'event_id' => $event->id,
                        'user_id' => $user->id,
                        'status' => 'pending',
                        'position' => $imported + 1,
                    ]);
                    $imported++;
                }
            } catch (\Exception $e) {
                $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
            }
        }

        return [
            'imported' => $imported,
            'errors' => $errors,
        ];
    }

    public function getWaitlistAsArray(Event $event): array
    {
        return Waitlist::where('event_id', $event->id)
            ->with('user')
            ->orderBy('position')
            ->get()
            ->map(function ($entry) {
                return [
                    'position' => $entry->position,
                    'name' => $entry->user->name,
                    'email' => $entry->user->email,
                    'status' => $entry->status,
                    'joined_date' => $entry->created_at->toDateTimeString(),
                    'notified_date' => $entry->notified_at?->toDateTimeString(),
                    'confirmed_date' => $entry->confirmed_at?->toDateTimeString(),
                ];
            })
            ->toArray();
    }
}
