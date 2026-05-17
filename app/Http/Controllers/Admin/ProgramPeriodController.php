<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramPeriod;
use Illuminate\Http\Request;

class ProgramPeriodController extends Controller
{
    public function index()
    {
        $periods = ProgramPeriod::withCount(['registrations as filled' => fn($q) => $q->whereNotIn('status', ['CANCELLED'])])
            ->orderByDesc('start_date')
            ->get();
        return view('admin.program.index', compact('periods'));
    }

    public function create()
    {
        return view('admin.program.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'price'      => 'required|integer|min:0',
            'dp_amount'  => 'required|integer|min:0',
            'quota'      => 'required|integer|min:1',
            'is_active'  => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        ProgramPeriod::create($data);
        return redirect()->route('admin.program.index')->with('success', 'Periode program berhasil dibuat.');
    }

    public function edit(ProgramPeriod $program)
    {
        $program->filled = $program->registrations()->whereNotIn('status', ['CANCELLED'])->count(); // single record, no N+1
        return view('admin.program.edit', compact('program'));
    }

    public function update(Request $request, ProgramPeriod $program)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'price'      => 'required|integer|min:0',
            'dp_amount'  => 'required|integer|min:0',
            'quota'      => 'required|integer|min:1',
            'is_active'  => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        $program->update($data);
        return redirect()->route('admin.program.index')->with('success', 'Periode program berhasil diperbarui.');
    }

    public function destroy(ProgramPeriod $program)
    {
        if ($program->registrations()->exists()) {
            return back()->withErrors(['error' => 'Periode ini memiliki pendaftar dan tidak dapat dihapus.']);
        }
        $program->delete();
        return redirect()->route('admin.program.index')->with('success', 'Periode program berhasil dihapus.');
    }
}
