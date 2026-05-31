<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactHistory;
use App\Models\ContactLog;
use App\Services\ContactHistoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function __construct(private ContactHistoryService $history) {}

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

        $sources    = Contact::select('source_file')->distinct()->whereNotNull('source_file')->pluck('source_file');
        $complaints = Contact::select('health_complaint')->distinct()->whereNotNull('health_complaint')
            ->orderBy('health_complaint')->pluck('health_complaint');

        $totalCount     = Contact::count();
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

        $this->history->record('create', "Tambah kontak: {$data['name']}", [
            ['type' => 'added', 'contact_id' => null, 'old' => null, 'new' => $data],
        ], auth()->id());

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

        $this->history->record('edit', "Edit kontak: {$contact->name}", [
            $this->history->buildModifiedChange($contact, $data),
        ], auth()->id());

        $contact->update($data);

        return redirect()->route('admin.kontak.index')
            ->with('success', 'Kontak berhasil diperbarui.');
    }

    // ── Delete ───────────────────────────────────────────────────────────────

    public function destroy(Contact $contact)
    {
        $this->history->record('delete', "Hapus kontak: {$contact->name}",
            $this->history->buildDeletedChanges(collect([$contact])),
            auth()->id()
        );

        $contact->delete();

        return redirect()->route('admin.kontak.index')
            ->with('success', 'Kontak dihapus.');
    }

    // ── Bulk Delete ──────────────────────────────────────────────────────────

    public function bulkDestroy(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'integer|exists:contacts,id']);

        $contacts = Contact::whereIn('id', $request->ids)->get();

        $this->history->record(
            'bulk_delete',
            "Bulk hapus {$contacts->count()} kontak",
            $this->history->buildDeletedChanges($contacts),
            auth()->id()
        );

        Contact::whereIn('id', $request->ids)->delete();

        return redirect()->route('admin.kontak.index')
            ->with('success', "{$contacts->count()} kontak berhasil dihapus.");
    }

    // ── Bulk Edit ────────────────────────────────────────────────────────────

    public function bulkUpdate(Request $request)
    {
        $allowedFields = [
            'health_complaint', 'info_source', 'source_file', 'notes', 'gender',
        ];

        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer|exists:contacts,id',
            'field' => 'required|string|in:' . implode(',', $allowedFields),
            'value' => 'nullable|string|max:500',
        ]);

        $field    = $request->field;
        $value    = $request->value ?: null;
        $contacts = Contact::whereIn('id', $request->ids)->get();

        $changes = $contacts->map(fn($c) => $this->history->buildModifiedChange($c, [$field => $value]))
            ->values()->all();

        $this->history->record(
            'bulk_edit',
            "Bulk edit {$contacts->count()} kontak: kolom {$field}",
            $changes,
            auth()->id()
        );

        Contact::whereIn('id', $request->ids)->update([$field => $value]);

        return redirect()->route('admin.kontak.index')
            ->with('success', "{$contacts->count()} kontak berhasil diperbarui.");
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

    // ── History ──────────────────────────────────────────────────────────────

    public function historyIndex()
    {
        $histories = ContactHistory::orderByDesc('created_at')->limit(3)->get();
        return view('admin.kontak.history', compact('histories'));
    }

    public function historyShow(ContactHistory $history)
    {
        $preview = $this->history->previewRestore($history);
        return view('admin.kontak.history-show', compact('history', 'preview'));
    }

    public function historyRestore(ContactHistory $history)
    {
        $affected = $this->history->applyRestore($history);
        return redirect()->route('admin.kontak.index')
            ->with('success', "Berhasil dipulihkan. {$affected} kontak dikembalikan ke kondisi sebelumnya.");
    }

    // ── Import CSV ───────────────────────────────────────────────────────────

    public function importForm()
    {
        return view('admin.kontak.import');
    }

    public function sampleCsv()
    {
        $cols = [
            'Nama Lengkap', 'No. Telepon', 'Email', 'Jenis Kelamin',
            'Alamat', 'Usia', 'Penyakit/Keluhan', 'Sumber Info', 'Asal File',
        ];

        $rows = [
            ['Budi Santoso',   '6281234567890', 'budi@email.com',       'Laki-laki', 'Jl. Mawar No. 5, Jakarta Selatan', '65', 'Diabetes',                      'Rekomendasi Teman', 'Webinar_2024'],
            ['Siti Rahayu',    '6285678901234', 'siti.rahayu@gmail.com','Perempuan', 'Jl. Melati No. 12, Bandung',       '72', 'Hipertensi',                     'Instagram',         'Talkshow_2024'],
            ['Andi Wijaya',    '6289012345678', '',                     'Laki-laki', 'Jl. Kenanga No. 3, Surabaya',      '58', 'Obesitas, Kolesterol Tinggi',    'WhatsApp',          'Webinar_2024'],
            ['Maria Dewi',     '6287890123456', 'maria.dewi@yahoo.com', 'Perempuan', '',                                  '70', 'Insomnia',                       'Rekomendasi Teman', 'Gereja_2024'],
            ['Hendra Gunawan', '6282345678901', '',                     '',          '',                                  '',   'Diabetes, Hipertensi',           '',                  'Webinar_2024'],
        ];

        // Generate as HTML table that Excel opens natively — avoids all CSV delimiter issues
        $th = fn(string $v) => '<th style="background:#2d6a4f;color:#fff;font-weight:bold;border:1px solid #ccc;padding:6px 10px;white-space:nowrap">' . htmlspecialchars($v) . '</th>';
        $td = fn(string $v, bool $phone = false) => '<td style="border:1px solid #ddd;padding:5px 10px;' .
            ($phone ? 'mso-number-format:\'@\';' : '') .
            (!$v ? 'color:#aaa;font-style:italic;' : '') . '">' .
            ($v ? htmlspecialchars($v) : '(kosong)') . '</td>';

        $html  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $html .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
        $html .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
                           xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
                           xmlns:o="urn:schemas-microsoft-com:office:office"
                           xmlns:x="urn:schemas-microsoft-com:office:excel">' . "\n";
        $html .= '<Worksheet ss:Name="Kontak"><Table>' . "\n";

        // Header row
        $html .= '<Row>';
        foreach ($cols as $col) {
            $html .= '<Cell><Data ss:Type="String">' . htmlspecialchars($col) . '</Data></Cell>';
        }
        $html .= '</Row>' . "\n";

        // Data rows
        foreach ($rows as $row) {
            $html .= '<Row>';
            foreach ($row as $i => $val) {
                $html .= '<Cell><Data ss:Type="String">' . htmlspecialchars($val) . '</Data></Cell>';
            }
            $html .= '</Row>' . "\n";
        }

        $html .= '</Table></Worksheet></Workbook>';

        return response($html, 200, [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="sample_kontak.xls"',
        ]);
    }

    public function importStore(Request $request)
    {
        $request->validate(['csv_file' => 'required|file|mimes:csv,txt|max:5120']);

        $path   = $request->file('csv_file')->getRealPath();
        $handle = fopen($path, 'r');

        if (!$handle) {
            return back()->withErrors(['csv_file' => 'Gagal membuka file.']);
        }

        // Auto-detect delimiter: Excel Indonesia pakai ';', internasional pakai ','
        $firstLine = fgets($handle);
        rewind($handle);
        $firstLine = ltrim($firstLine, "\xEF\xBB\xBF");
        $delimiter = substr_count($firstLine, ';') >= substr_count($firstLine, ',') ? ';' : ',';

        // Header row
        $rawHeader = fgetcsv($handle, 0, $delimiter);
        if (!$rawHeader) {
            fclose($handle);
            return back()->withErrors(['csv_file' => 'File CSV kosong atau format tidak valid.']);
        }
        $rawHeader[0] = ltrim($rawHeader[0], "\xEF\xBB\xBF");
        $headers = array_map(fn($h) => strtolower(trim($h)), $rawHeader);

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

        $colIndex = [];
        foreach ($headers as $i => $h) {
            if (isset($map[$h])) $colIndex[$i] = $map[$h];
        }

        if (empty($colIndex)) {
            fclose($handle);
            return back()->withErrors([
                'csv_file' => 'Tidak ada kolom yang dikenali. Header ditemukan: ' . implode(' | ', $headers),
            ]);
        }

        $inserted       = 0;
        $updated        = 0;
        $skipped        = 0;
        $addedChanges   = [];

        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            if (empty(array_filter($row))) continue;

            $data = [];
            foreach ($colIndex as $pos => $col) {
                $raw = isset($row[$pos]) ? trim($row[$pos]) : null;
                if ($raw === '' || $raw === null) { $data[$col] = null; continue; }
                if (!mb_check_encoding($raw, 'UTF-8')) {
                    $raw = mb_convert_encoding($raw, 'UTF-8', 'Windows-1252');
                }
                $data[$col] = $raw;
            }

            if (empty($data['name']) || empty($data['phone'])) {
                $skipped++;
                continue;
            }

            // Fix Excel scientific notation phone numbers
            $phone = $data['phone'];
            if (preg_match('/^[\d.]+[eE][+\-]?\d+$/', $phone)) {
                $phone = number_format((float) $phone, 0, '.', '');
            }
            $data['phone'] = preg_replace('/[\s\-]/', '', $phone);

            if (isset($data['age'])) {
                $data['age'] = is_numeric($data['age']) ? (int) $data['age'] : null;
            }

            $existing = Contact::where('phone', $data['phone'])->first();
            if ($existing) {
                $existing->update($data);
                $updated++;
            } else {
                Contact::create($data);
                $inserted++;
                $addedChanges[] = ['type' => 'added', 'contact_id' => null, 'old' => null, 'new' => $data];
            }
        }

        fclose($handle);

        // Record history only if something was inserted
        if ($inserted > 0 && !empty($addedChanges)) {
            $this->history->record(
                'import',
                "Import CSV: {$inserted} kontak baru" . ($updated > 0 ? ", {$updated} diperbarui" : ''),
                $addedChanges,
                auth()->id()
            );
        }

        $msg = "Import selesai: {$inserted} kontak baru, {$updated} diperbarui";
        if ($skipped > 0) $msg .= ", {$skipped} baris dilewati (nama/telepon kosong)";
        $msg .= '.';

        return redirect()->route('admin.kontak.index')->with('success', $msg);
    }
}
