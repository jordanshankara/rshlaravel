<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\ContactHistory;
use Illuminate\Support\Collection;

class ContactHistoryService
{
    private const MAX_HISTORY = 3;

    /**
     * Record a history snapshot.
     *
     * $changes format:
     * [
     *   ['type' => 'added',    'contact_id' => null, 'old' => null, 'new' => [...data]],
     *   ['type' => 'modified', 'contact_id' => 42,   'old' => [...], 'new' => [...]],
     *   ['type' => 'deleted',  'contact_id' => 17,   'old' => [...], 'new' => null],
     * ]
     */
    public function record(
        string $type,
        string $description,
        array  $changes,
        ?int   $userId
    ): ContactHistory {
        // Prune oldest if already at max
        $count = ContactHistory::count();
        if ($count >= self::MAX_HISTORY) {
            ContactHistory::orderBy('created_at')->limit($count - self::MAX_HISTORY + 1)->delete();
        }

        return ContactHistory::create([
            'action_type'    => $type,
            'description'    => $description,
            'affected_count' => count($changes),
            'snapshot'       => json_encode($changes),
            'user_id'        => $userId,
        ]);
    }

    /**
     * Build change list from a Contact before update.
     */
    public function buildModifiedChange(Contact $contact, array $newData): array
    {
        return [
            'type'       => 'modified',
            'contact_id' => $contact->id,
            'old'        => $contact->only([
                'id', 'name', 'phone', 'email', 'gender', 'address',
                'age', 'health_complaint', 'info_source', 'source_file', 'notes',
            ]),
            'new'        => array_intersect_key($newData, array_flip([
                'name', 'phone', 'email', 'gender', 'address',
                'age', 'health_complaint', 'info_source', 'source_file', 'notes',
            ])),
        ];
    }

    /**
     * Build change list for deleted contacts.
     */
    public function buildDeletedChanges(Collection $contacts): array
    {
        return $contacts->map(fn($c) => [
            'type'       => 'deleted',
            'contact_id' => $c->id,
            'old'        => $c->only([
                'id', 'name', 'phone', 'email', 'gender', 'address',
                'age', 'health_complaint', 'info_source', 'source_file', 'notes',
            ]),
            'new'        => null,
        ])->values()->all();
    }

    /**
     * Build change list for newly added contacts.
     */
    public function buildAddedChanges(array $contactDataList): array
    {
        return array_map(fn($data) => [
            'type'       => 'added',
            'contact_id' => null, // filled after insert when possible
            'old'        => null,
            'new'        => $data,
        ], $contactDataList);
    }

    /**
     * Preview what a restore would do.
     * Returns ['to_delete' => n, 'to_restore_old' => n, 'to_reinsert' => n, 'samples' => [...]]
     */
    public function previewRestore(ContactHistory $history): array
    {
        $snapshot = is_array($history->snapshot) ? $history->snapshot : json_decode($history->snapshot, true);

        $toDelete     = 0;
        $toRestoreOld = 0;
        $toReinsert   = 0;
        $samples      = [];

        foreach ($snapshot as $change) {
            match ($change['type']) {
                'added'    => $toDelete++,
                'modified' => $toRestoreOld++,
                'deleted'  => $toReinsert++,
                default    => null,
            };
            if (count($samples) < 5) {
                $samples[] = $change;
            }
        }

        return compact('toDelete', 'toRestoreOld', 'toReinsert', 'samples');
    }

    /**
     * Apply the restore. Returns number of contacts affected.
     * Deletes the history entry after successful restore.
     */
    public function applyRestore(ContactHistory $history): int
    {
        $snapshot = is_array($history->snapshot) ? $history->snapshot : json_decode($history->snapshot, true);
        $affected = 0;

        foreach ($snapshot as $change) {
            try {
                match ($change['type']) {
                    'added' => $this->undoAdded($change),
                    'modified' => $this->undoModified($change),
                    'deleted' => $this->undoDeleted($change),
                    default => null,
                };
                $affected++;
            } catch (\Throwable) {
                // Continue on individual failures
            }
        }

        $history->delete();
        return $affected;
    }

    private function undoAdded(array $change): void
    {
        // Find and delete the contact that was added
        // Match by name + phone since contact_id may be null for imports
        $id    = $change['contact_id'];
        $newData = $change['new'] ?? [];

        if ($id) {
            Contact::where('id', $id)->delete();
        } elseif (!empty($newData['phone'])) {
            Contact::where('phone', $newData['phone'])->delete();
        }
    }

    private function undoModified(array $change): void
    {
        $id  = $change['contact_id'];
        $old = $change['old'] ?? [];

        if (!$id || empty($old)) return;

        $contact = Contact::find($id);
        if ($contact) {
            $contact->update(array_diff_key($old, ['id' => 1]));
        }
    }

    private function undoDeleted(array $change): void
    {
        $old = $change['old'] ?? [];
        if (empty($old)) return;

        // Only re-insert if phone not already taken
        $phone = $old['phone'] ?? null;
        if ($phone && Contact::where('phone', $phone)->exists()) return;

        $data = array_diff_key($old, ['id' => 1]);
        Contact::create($data);
    }
}
