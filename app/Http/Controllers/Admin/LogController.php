<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogController extends Controller
{
    private const MAX_ENTRIES = 300;
    private const TAIL_LINES  = 10000;

    public function index(Request $request)
    {
        $level   = $request->query('level', 'all');
        $search  = $request->query('q', '');
        $entries = $this->parseLog($level, $search);
        $counts  = $this->levelCounts();

        return view('admin.log.index', compact('entries', 'level', 'search', 'counts'));
    }

    public function clear()
    {
        $path = storage_path('logs/laravel.log');
        if (file_exists($path)) {
            file_put_contents($path, '');
        }
        return redirect()->route('admin.log.index')->with('success', 'Log berhasil dihapus.');
    }

    // ── Parsing ──────────────────────────────────────────────────────────────

    private function parseLog(string $filterLevel, string $search): array
    {
        $path = storage_path('logs/laravel.log');
        if (!file_exists($path) || filesize($path) === 0) {
            return [];
        }

        $lines   = $this->tailLines($path, self::TAIL_LINES);
        $entries = [];
        $current = null;

        foreach ($lines as $line) {
            if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+)\.(\w+): (.*)$/', $line, $m)) {
                if ($current) {
                    $entries[] = $current;
                }
                $current = [
                    'datetime' => $m[1],
                    'env'      => $m[2],
                    'level'    => strtolower($m[3]),
                    'message'  => $m[4],
                    'trace'    => '',
                ];
            } elseif ($current && trim($line) !== '') {
                $current['trace'] .= $line . "\n";
            }
        }
        if ($current) {
            $entries[] = $current;
        }

        $entries = array_reverse($entries);

        if ($filterLevel !== 'all') {
            $entries = array_values(array_filter($entries, fn($e) => $e['level'] === $filterLevel));
        }

        if ($search !== '') {
            $entries = array_values(array_filter($entries, function ($e) use ($search) {
                return stripos($e['message'], $search) !== false
                    || stripos($e['trace'], $search) !== false;
            }));
        }

        return array_slice($entries, 0, self::MAX_ENTRIES);
    }

    private function levelCounts(): array
    {
        $path = storage_path('logs/laravel.log');
        if (!file_exists($path)) {
            return [];
        }

        $counts = [];
        foreach ($this->tailLines($path, self::TAIL_LINES) as $line) {
            if (preg_match('/^\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\] \w+\.(\w+):/', $line, $m)) {
                $level          = strtolower($m[1]);
                $counts[$level] = ($counts[$level] ?? 0) + 1;
            }
        }
        arsort($counts);
        return $counts;
    }

    private function tailLines(string $path, int $limit): array
    {
        $file = new \SplFileObject($path, 'r');
        $file->seek(PHP_INT_MAX);
        $total = $file->key();
        $start = max(0, $total - $limit);

        $lines = [];
        $file->seek($start);
        while (!$file->eof()) {
            $line = $file->fgets();
            if ($line !== false) {
                $lines[] = rtrim($line);
            }
        }
        return $lines;
    }
}
