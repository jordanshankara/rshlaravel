<?php
/**
 * RSH Satu Bumi — Safe Deploy Script
 *
 * Upload to public_html/, access via browser ONCE, then DELETE immediately.
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
// DirectAdmin layout: deploy.php lives in public_html/, Laravel root is ../laravel/
// Standard layout: deploy.php in public/, Laravel root is parent dir.
if (is_file(dirname(__DIR__) . '/laravel/artisan')) {
    define('LARAVEL_ROOT', dirname(__DIR__) . '/laravel');
} elseif (is_file(dirname(__DIR__) . '/artisan')) {
    define('LARAVEL_ROOT', dirname(__DIR__));
} else {
    define('LARAVEL_ROOT', __DIR__);
}

define('ARTISAN', PHP_BINARY . ' ' . LARAVEL_ROOT . '/artisan --no-ansi');

set_time_limit(300);
ini_set('output_buffering', 'off');
ini_set('zlib.output_compression', false);
while (ob_get_level()) ob_end_flush();
ob_implicit_flush(true);

function run(string $cmd): array
{
    exec($cmd . ' 2>&1', $output, $code);
    return ['cmd' => $cmd, 'output' => implode("\n", $output), 'code' => $code];
}

function ok(string $msg): void   { echo "OK  {$msg}\n"; flush(); }
function warn(string $msg): void  { echo "WRN {$msg}\n"; flush(); }
function fail(string $msg): void  { echo "ERR {$msg}\n"; flush(); }
function section(string $t): void { echo "\n-- {$t} " . str_repeat('-', max(0, 50 - strlen($t))) . "\n"; flush(); }

header('Content-Type: text/plain; charset=utf-8');
header('X-Accel-Buffering: no');
echo "RSH Satu Bumi -- Deploy Script\n";
echo date('Y-m-d H:i:s T') . "\n";
echo str_repeat('=', 55) . "\n";
echo "LARAVEL_ROOT: " . LARAVEL_ROOT . "\n";
flush();

// ── Guard 1: .env must exist ─────────────────────────────────────────────────
section('Guard: .env');
$envPath = LARAVEL_ROOT . '/.env';
if (!file_exists($envPath)) {
    fail('.env NOT found. Deploy aborted. Create .env from .env.example first.');
    exit(1);
}
ok('.env exists');

$envContent = file_get_contents($envPath);
preg_match('/^APP_DEBUG\s*=\s*(.+)$/m', $envContent, $m);
$appDebug = trim($m[1] ?? 'unknown');
preg_match('/^APP_ENV\s*=\s*(.+)$/m', $envContent, $m);
$appEnv = trim($m[1] ?? 'unknown');

if (strtolower($appDebug) === 'true') {
    warn("APP_DEBUG=true -- set to false for production!");
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
    echo "This prevents accidental execution.\n";
    exit(1);
}
ok('Safety token verified');
flush();

// ── Step 1: Composer install ─────────────────────────────────────────────────
section('Composer Install');
$composerBin = LARAVEL_ROOT . '/vendor/bin/composer';
if (!file_exists($composerBin)) {
    $composerBin = 'composer';
}
echo "Running composer install...\n"; flush();
$result = run(PHP_BINARY . " {$composerBin} install --no-dev --optimize-autoloader --no-interaction --working-dir=" . escapeshellarg(LARAVEL_ROOT));
if ($result['code'] === 0) {
    ok('composer install completed');
} else {
    warn('composer install exit=' . $result['code']);
    echo $result['output'] . "\n";
}
flush();

// ── Step 2: Migrate (never fresh, never seed) ────────────────────────────────
section('Database -- Migrate');
echo "Running: php artisan migrate --force\n";
echo "(Only pending migrations -- existing data is safe)\n\n";
flush();

$result = run(ARTISAN . ' migrate --force');
echo $result['output'] . "\n";
if ($result['code'] === 0) {
    ok('migrate --force completed');
} else {
    fail('Migration failed (exit ' . $result['code'] . ') -- investigate before proceeding');
}
flush();

// ── Step 3: Clear caches ─────────────────────────────────────────────────────
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
    flush();
}

// ── Step 4: Storage symlink ──────────────────────────────────────────────────
section('Storage Symlink');
$symlinkPath = LARAVEL_ROOT . '/public/storage';

if (is_link($symlinkPath)) {
    ok("Symlink exists -> " . readlink($symlinkPath));
} elseif (is_dir($symlinkPath)) {
    warn('public/storage is a real directory, not a symlink');
} else {
    echo "Creating storage symlink...\n"; flush();
    $result = run(ARTISAN . ' storage:link');
    if ($result['code'] === 0) {
        ok('storage:link created');
    } else {
        warn('storage:link failed: ' . $result['output']);
    }
}
flush();

// ── Step 5: Health summary ───────────────────────────────────────────────────
section('Post-Deploy Health');
echo "APP_DEBUG : {$appDebug}\n";
echo "APP_ENV   : {$appEnv}\n";
if (strtolower($appDebug) === 'true') {
    fail("APP_DEBUG=true -- MUST be false in production");
} else {
    ok("APP_DEBUG=false");
}
if (is_link($symlinkPath) || is_dir($symlinkPath)) {
    ok("Storage symlink: OK");
} else {
    fail("Storage symlink: MISSING -- images will 404");
}

echo "\n" . str_repeat('=', 55) . "\n";
echo "Deploy complete -- " . date('H:i:s') . "\n";
echo "\nDELETE this file from the server now:\n";
echo "  " . __FILE__ . "\n";
