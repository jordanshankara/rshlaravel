<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    // ── Index ────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Contact::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($complaint = $request->get('complaint')) {
            $query->where('health_complaint', 'like', "%{$complaint}%");
        }

        if ($source = $request->get('source')) {
            $query->where('source_file', 'like', "%{$source}%");
        }

        if ($request->get('not_contacted')) {
            $query->whereNull('last_contacted_at');
        }

        $contacts = $query->orderBy('name')->paginate(30)->withQueryString();

        // For filter dropdowns — distinct values
        $sources    = Contact::select('source_file')->distinct()->whereNotNull('source_file')->pluck('source_file');
        $complaints = Contact::select('health_complaint')->distinct()->whereNotNull('health_complaint')
            ->orderBy('health_complaint')->pluck('health_complaint');

        $totalCount    = Contact::count();
        $contactedCount = Contact::whereNotNull('last_contacted_at')->count();

        return view('admin.kontak.index', compact(
            'contacts', 'sources', 'complaints', 'totalCount', 'contactedCount'
        ));
    }

    // ── Create / Store ───────────────────────────────────────────────────────

    public function create()
    {
        return view('admin.kontak.form', ['contact' => new Contact()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'phone'            => 'required|string|max:30|unique:contacts,phone',
            'email'            => 'nullable|email|max:255',
            'gender'           => 'nullable|string|max:20',
            'address'          => 'nullable|string',
            'age'              => 'nullable|integer|min:1|max:120',
            'health_complaint' => 'nullable|string',
            'info_source'      => 'nullable|string|max:255',
            'source_file'      => 'nullable|string|max:100',
            'notes'            => 'nullable|string',
        ]);

        Contact::create($data);

        return redirect()->route('admin.kontak.index')
            ->with('success', 'Kontak berhasil ditambahkan.');
    }

    // ── Edit / Update ────────────────────────────────────────────────────────

    public function edit(Contact $contact)
    {
        return view('admin.kontak.form', compact('contact'));
    }

    public function update(Request $request, Contact $contact)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'phone'            => 'required|string|max:30|unique:contacts,phone,' . $contact->id,
            'email'            => 'nullable|email|max:255',
            'gender'           => 'nullable|string|max:20',
            'address'          => 'nullable|string',
            'age'              => 'nullable|integer|min:1|max:120',
            'health_complaint' => 'nullable|string',
            'info_source'      => 'nullable|string|max:255',
            'source_file'      => 'nullable|string|max:100',
            'notes'            => 'nullable|string',
        ]);

        $contact->update($data);

        return redirect()->route('admin.kontak.index')
            ->with('success', 'Kontak berhasil diperbarui.');
    }

    // ── Delete ───────────────────────────────────────────────────────────────

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('admin.kontak.index')
            ->with('success', 'Kontak dihapus.');
    }

    // ── Log Contact (AJAX) ───────────────────────────────────────────────────

    public function log(Request $request, Contact $contact): JsonResponse
    {
        $request->validate(['type' => 'required|in:wa,email']);

        $now = now();

        ContactLog::create([
            'contact_id'   => $contact->id,
            'type'         => $request->type,
            'user_id'      => auth()->id(),
            'contacted_at' => $now,
        ]);

        $contact->update([
            'last_contacted_at'   => $now,
            'last_contacted_type' => $request->type,
        ]);

        return response()->json([
            'success'      => true,
            'contacted_at' => $now->setTimezone('Asia/Jakarta')->format('d M Y, H:i'),
            'type'         => $request->type,
        ]);
    }

    // ── Import CSV ───────────────────────────────────────────────────────────

    public function importForm()
    {
        return view('admin.kontak.import');
    }

    public function importStore(Request $request)
    {
        $request->validate(['csv_file' => 'required|file|mimes:csv,txt|max:5120']);

        $path    = $request->file('csv_file')->getRealPath();
        $handle  = fopen($path, 'r');

        if (!$handle) {
            return back()->withErrors(['csv_file' => 'Gagal membuka file.']);
        }

        // Read header row — detect BOM and normalize
        $rawHeader = fgetcsv($handle);
        if (!$rawHeader) {
            fclose($handle);
            return back()->withErrors(['csv_file' => 'File CSV kosong atau format tidak valid.']);
        }

        // Strip BOM from first column header
        $rawHeader[0] = ltrim($rawHeader[0], "\xEF\xBB\xBF");

        // Normalize headers: lowercase + trim
        $headers = array_map(fn($h) => strtolower(trim($h)), $rawHeader);

        // Column mapping: CSV header → DB column
        $map = [
            'nama lengkap'     => 'name',
            'no. telepon'      => 'phone',
            'no telepon'       => 'phone',
            'email'            => 'email',
            'jenis kelamin'    => 'gender',
            'alamat'           => 'address',
            'usia'             => 'age',
            'penyakit/keluhan' => 'health_complaint',
            'penyakit keluhan' => 'health_complaint',
            'sumber info'      => 'info_source',
            'asal file'        => 'source_file',
        ];

        // Build index: position → db_column
        $colIndex = [];
        foreach ($headers as $i => $h) {
            if (isset($map[$h])) {
                $colIndex[$i] = $map[$h];
            }
        }

        $inserted = 0;
        $updated  = 0;
        $skipped  = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (empty(array_filter($row))) continue; // skip blank rows

            $data = [];
            foreach ($colIndex as $pos => $col) {
                $val = isset($row[$pos]) ? trim($row[$pos]) : null;
                $data[$col] = ($val === '' || $val === null) ? null : $val;
            }

            if (empty($data['name']) || empty($data['phone'])) {
                $skipped++;
                continue;
            }

            // Normalize phone: ensure starts with digits, strip spaces/dashes
            $data['phone'] = preg_replace('/[\s\-]/', '', $data['phone']);

            // Age: integer only
            if (isset($data['age'])) {
                $data['age'] = is_numeric($data['age']) ? (int) $data['age'] : null;
            }

            // Upsert by phone
            $existing = Contact::where('phone', $data['phone'])->first();
            if ($existing) {
                $existing->update($data);
                $updated++;
            } else {
                Contact::create($data);
                $inserted++;
            }
        }

        fclose($handle);

        $msg = "Import selesai: {$inserted} kontak baru, {$updated} diperbarui";
        if ($skipped > 0) $msg .= ", {$skipped} baris dilewati (nama/telepon kosong)";
        $msg .= '.';

        return redirect()->route('admin.kontak.index')->with('success', $msg);
    }
}
