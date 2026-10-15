$ErrorActionPreference = 'Stop'

. "$PSScriptRoot\lib-env.ps1"

$config = Get-DbConfig

Write-Host "`n===== CAU HINH DATABASE =====" -ForegroundColor Cyan
Write-Host "Host     : $($config.Host)"
Write-Host "Port     : $($config.Port)"
Write-Host "Database : $($config.Name)"
Write-Host "Username : $($config.User)"
Write-Host "Password : [AN]"

Write-Host "`n===== KIEM TRA KET NOI =====" -ForegroundColor Cyan

$version = Invoke-MySqlReadOnly `
    -Sql "SELECT VERSION() AS mysql_version;" `
    -Database $config.Name `
    -User $config.User `
    -Pass $config.Pass `
    -HostName $config.Host `
    -Port $config.Port

$version | ForEach-Object { Write-Host $_ }

Write-Host "`n===== DANH SACH BANG =====" -ForegroundColor Cyan

$tables = Invoke-MySqlReadOnly `
    -Sql "SHOW TABLES;" `
    -Database $config.Name `
    -User $config.User `
    -Pass $config.Pass `
    -HostName $config.Host `
    -Port $config.Port

$tables | ForEach-Object { Write-Host $_ }

Write-Host "`n===== TRANG THAI MIGRATION LARAVEL =====" -ForegroundColor Cyan

Push-Location $script:Laravel

try {
    php artisan migrate:status
    if ($LASTEXITCODE -ne 0) {
        throw 'Khong doc duoc trang thai migration Laravel.'
    }
}
finally {
    Pop-Location
}

Write-Host "`nHoan tat kiem tra. Script khong tao, sua hoac xoa bang." `
    -ForegroundColor Green
