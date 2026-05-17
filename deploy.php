<?php
/**
 * RSH Satu Bumi — Safe Deploy Script
 *
 * Upload to server root, access via browser ONCE, then DELETE immediately.
 * NEVER run this on a local dev machine.
 *
 * What this script does (in order):
 *   1. Verify .env exists (aborts if missing — never overwrites it)
 *   2. Run: composer install --no-dev --optimize-autoloader
 *   3. Run: php artisan migrate --force  (pending migrations only — NEVER fresh)
 *   4. Clear: config, cache, view, route caches
 *   5. Verify/recreate storage symlink
 *   6. Show post-deploy health check
 *
 * NEVER runs: migrate:fresh, migrate:reset, db:seed
 */

// ── Bootstrap ────────────────────────────────────────────────────────────────
define('LARAVEL_ROOT', __DIR__);
define('ARTISAN',      PHP_BINARY . ' ' . LARAVEL_ROOT . '/artisan');
define('COMPOSER',     PHP_BINARY . ' ' . LARAVEL_ROOT . '/vendor/bin/composer');

set_time_limit(300);
ini_set('output_buffering', 0);

function run(string $cmd): array
{
    exec($cmd . ' 2>&1', $output, $code);
    return ['cmd' => $cmd, 'output' => implode("\n", $output), 'code' => $code];
}

function ok(string $msg): void  { echo "✅ {$msg}\n"; }
function warn(string $msg): void { echo "⚠️  {$msg}\n"; }
function fail(string $msg): void { echo "❌ {$msg}\n"; }
function section(string $title): void { echo "\n── {$title} " . str_repeat('─', max(0, 50 - strlen($title))) . "\n"; }

header('Content-Type: text/plain; charset=utf-8');
echo "RSH Satu Bumi — Deploy Script\n";
echo date('Y-m-d H:i:s T') . "\n";
echo str_repeat('═', 55) . "\n";

// ── Guard 1: .env must exist ─────────────────────────────────────────────────
section('Guard: .env');
$envPath = LARAVEL_ROOT . '/.env';
if (!file_exists($envPath)) {
    fail('.env file NOT found. Deploy aborted.');
    echo "\nCreate .env from .env.example and configure all values before deploying.\n";
    exit(1);
}
ok('.env exists');

// Parse key env values for checks
$envContent = file_get_contents($envPath);
preg_match('/^APP_DEBUG\s*=\s*(.+)$/m', $envContent, $m);
$appDebug = trim($m[1] ?? 'unknown');
preg_match('/^APP_ENV\s*=\s*(.+)$/m', $envContent, $m);
$appEnv = trim($m[1] ?? 'unknown');

if (strtolower($appDebug) === 'true') {
    warn("APP_DEBUG=true in .env — set to false for production!");
} else {
    ok("APP_DEBUG={$appDebug}");
}
ok("APP_ENV={$appEnv}");

// ── Guard 2: Safety confirmation token ──────────────────────────────────────
section('Guard: Safety Token');
$token = $_GET['confirm'] ?? '';
if ($token !== 'DEPLOY_RSH_' . date('Ymd')) {
    warn('Missing or wrong ?confirm= token. To run deploy, append:');
    echo '  ?confirm=DEPLOY_RSH_' . date('Ymd') . "\n";
    echo "\nThis prevents accidental execution.\n";
    exit(1);
}
ok('Safety token verified');

// ── Step 1: Migration count before ───────────────────────────────────────────
section('Database — Before');
$bootstrap = require LARAVEL_ROOT . '/bootstrap/app.php';
$app = $bootstrap->make(\Illuminate\Contracts\Console\Kernel::class);
$app->bootstrap();

try {
    $migrationsBefore = \Illuminate\Support\Facades\DB::table('migrations')->count();
    ok("Migrations already run: {$migrationsBefore}");
    $dbOk = true;
} catch (\Throwable $e) {
    fail('DB connection failed: ' . $e->getMessage());
    $dbOk = false;
    $migrationsBefore = 0;
}

// ── Step 2: Composer install ─────────────────────────────────────────────────
section('Composer Install');
$composerBin = LARAVEL_ROOT . '/vendor/bin/composer';
if (!file_exists($composerBin)) {
    // Try system composer
    $composerBin = 'composer';
}
$result = run(PHP_BINARY . " {$composerBin} install --no-dev --optimize-autoloader --working-dir=" . LARAVEL_ROOT);
if ($result['code'] === 0) {
    ok('composer install completed');
} else {
    warn('composer install had issues (exit ' . $result['code'] . ')');
    echo $result['output'] . "\n";
}

// ── Step 3: Migrate (never fresh, never seed) ────────────────────────────────
section('Database — Migrate');
echo "Running: php artisan migrate --force\n";
echo "(Only pending migrations — existing data is safe)\n\n";

$result = run(ARTISAN . ' migrate --force');
echo $result['output'] . "\n";
if ($result['code'] === 0) {
    ok('migrate --force completed');
} else {
    fail('Migration failed (exit ' . $result['code'] . ') — investigate before proceeding');
}

// Migration count after
if ($dbOk) {
    try {
        $migrationsAfter = \Illuminate\Support\Facades\DB::table('migrations')->count();
        $newMigrations = $migrationsAfter - $migrationsBefore;
        ok("Migrations after: {$migrationsAfter} (+{$newMigrations} new)");
    } catch (\Throwable $e) {
        warn('Could not recount migrations: ' . $e->getMessage());
    }
}

// ── Step 4: Clear caches ─────────────────────────────────────────────────────
section('Clear Caches');
foreach ([
    'config:clear'  => 'Config cache',
    'cache:clear'   => 'Application cache',
    'view:clear'    => 'View cache',
    'route:clear'   => 'Route cache',
] as $cmd => $label) {
    $result = run(ARTISAN . " {$cmd}");
    if ($result['code'] === 0) {
        ok("{$label} cleared");
    } else {
        warn("{$label} clear failed: " . $result['output']);
    }
}

// ── Step 5: Storage symlink ──────────────────────────────────────────────────
section('Storage Symlink');
$symlinkPath  = LARAVEL_ROOT . '/public/storage';
$symlinkTarget = LARAVEL_ROOT . '/storage/app/public';

if (is_link($symlinkPath)) {
    $actual = readlink($symlinkPath);
    ok("Symlink exists → {$actual}");
} elseif (is_dir($symlinkPath)) {
    warn('public/storage is a directory (not a symlink) — attempting storage:link');
    $result = run(ARTISAN . ' storage:link');
    echo $result['output'] . "\n";
} else {
    echo "Creating storage symlink...\n";
    $result = run(ARTISAN . ' storage:link');
    if ($result['code'] === 0) {
        ok('storage:link created');
    } else {
        warn('storage:link failed: ' . $result['output']);
    }
}

// ── Step 6: Health summary ───────────────────────────────────────────────────
section('Post-Deploy Health');

// DB
if ($dbOk) {
    ok("Database: connected");
} else {
    fail("Database: connection failed");
}

// APP_DEBUG warning
if (strtolower($appDebug) === 'true') {
    fail("APP_DEBUG=true — MUST be false in production (set in .env on server)");
} else {
    ok("APP_DEBUG=false ✓");
}

// Symlink
if (is_link($symlinkPath) || is_dir($symlinkPath)) {
    ok("Storage symlink: OK");
} else {
    fail("Storage symlink: MISSING — images will 404");
}

echo "\n" . str_repeat('═', 55) . "\n";
echo "Deploy complete — " . date('H:i:s') . "\n";
echo "\n⚠️  DELETE this file from the server now:\n";
echo "   rm " . __FILE__ . "\n";
echo "   or via FTP/File Manager immediately after reviewing output.\n";
