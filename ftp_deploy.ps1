param()

$ftpHost  = "ftp://server20.hostwhitelabel.com"
$ftpUser  = "rshsa678"
$ftpPass  = "WuwyyjBmPfCEr99R5JTw"
$localRoot  = "F:\0. Code\rshsatubumi-laravel"
$remoteRoot = "/domains/rshsatubumi.id/laravel"
$remotePublicHtml = "/public_html"

$changedFiles = @(
    "app/Http/Controllers/Admin/ArtikelController.php",
    "app/Http/Controllers/Admin/LogController.php",
    "app/Http/Controllers/Admin/PengaturanController.php",
    "app/Http/Controllers/Public/PendaftaranController.php",
    "app/Http/Middleware/Authenticate.php",
    "app/Mail/CustomMailManager.php",
    "app/Providers/AppServiceProvider.php",
    "app/Services/EmailService.php",
    "app/Services/RegistrationInvoiceService.php",
    "app/Services/SheetsService.php",
    "config/session.php",
    "resources/views/admin/artikel/create.blade.php",
    "resources/views/admin/artikel/edit.blade.php",
    "resources/views/admin/artikel/index.blade.php",
    "resources/views/admin/dashboard.blade.php",
    "resources/views/admin/invoice-product/index.blade.php",
    "resources/views/admin/invoice/create.blade.php",
    "resources/views/admin/invoice/edit.blade.php",
    "resources/views/admin/invoice/index.blade.php",
    "resources/views/admin/invoice/pdf.blade.php",
    "resources/views/admin/log/index.blade.php",
    "resources/views/admin/pengaturan/index.blade.php",
    "resources/views/admin/pengguna/index.blade.php",
    "resources/views/admin/program/create.blade.php",
    "resources/views/admin/program/edit.blade.php",
    "resources/views/admin/program/index.blade.php",
    "resources/views/admin/registrasi/index.blade.php",
    "resources/views/admin/registrasi/show.blade.php",
    "resources/views/errors/404.blade.php",
    "resources/views/layouts/admin.blade.php",
    "resources/views/layouts/partials/admin-sidebar.blade.php",
    "resources/views/public/pendaftaran/form.blade.php",
    "routes/web.php"
)

function Upload-File([string]$localPath, [string]$remotePath) {
    $uri = $ftpHost + $remotePath
    try {
        $req = [System.Net.FtpWebRequest]::Create($uri)
        $req.Method = [System.Net.WebRequestMethods+Ftp]::UploadFile
        $req.Credentials = New-Object System.Net.NetworkCredential($ftpUser, $ftpPass)
        $req.UseBinary = $true
        $req.UsePassive = $true
        $req.KeepAlive = $false
        $req.Timeout = 30000

        $fileBytes = [System.IO.File]::ReadAllBytes($localPath)
        $req.ContentLength = $fileBytes.Length

        $stream = $req.GetRequestStream()
        $stream.Write($fileBytes, 0, $fileBytes.Length)
        $stream.Close()

        $resp = $req.GetResponse()
        $resp.Close()
        Write-Host ("OK  " + $remotePath) -ForegroundColor Green
        return $true
    } catch {
        Write-Host ("ERR " + $remotePath + " -- " + $_.Exception.Message) -ForegroundColor Red
        return $false
    }
}

$ok = 0
$fail = 0

foreach ($file in $changedFiles) {
    $localPath = Join-Path $localRoot ($file -replace '/', '\')
    $remotePath = $remoteRoot + "/" + $file

    if (-not (Test-Path $localPath)) {
        Write-Host ("SKP " + $file + " (not found locally)") -ForegroundColor Yellow
        continue
    }

    if (Upload-File $localPath $remotePath) { $ok++ } else { $fail++ }
}

$deployLocal  = Join-Path $localRoot "deploy.php"
$deployRemote = $remotePublicHtml + "/deploy.php"
Write-Host ""
Write-Host "Uploading deploy.php to public_html..." -ForegroundColor Cyan
if (Upload-File $deployLocal $deployRemote) { $ok++ } else { $fail++ }

Write-Host ""
Write-Host "================================"
Write-Host ("Done: " + $ok + " uploaded, " + $fail + " failed")
$today = Get-Date -Format "yyyyMMdd"
$deployUrl = "https://rshsatubumi.id/deploy.php" + [char]63 + "confirm=DEPLOY_RSH_" + $today
Write-Host ""
Write-Host "Trigger deploy at:"
Write-Host $deployUrl
