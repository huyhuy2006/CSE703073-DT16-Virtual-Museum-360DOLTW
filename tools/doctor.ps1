$ErrorActionPreference = 'Stop'

$Root = Split-Path $PSScriptRoot -Parent
$Laravel = Join-Path $Root '02_SourceCode\laravel'

function Write-Check {
    param(
        [string]$Label,
        [scriptblock]$Command
    )

    Write-Host "`n[$Label]" -ForegroundColor Cyan

    try {
        & $Command
    }
    catch {
        Write-Host "LOI: $($_.Exception.Message)" -ForegroundColor Red
    }
}

Write-Host '===== BAOTANG360 ENVIRONMENT DOCTOR =====' `
    -ForegroundColor Green

Write-Host "Project root: $Root"
Write-Host "Laravel root: $Laravel"

Write-Check 'PHP' {
    php -v
}

Write-Check 'Composer' {
    composer --version
}

Write-Check 'Laravel version' {
    Push-Location $Laravel
    try {
        php artisan --version
        if ($LASTEXITCODE -ne 0) {
            throw 'Khong doc duoc phien ban Laravel.'
        }
    }
    finally {
        Pop-Location
    }
}

Write-Check 'Python' {
    python --version
}

Write-Check 'MySQL CLI' {
    $mysql = Get-Command mysql -ErrorAction SilentlyContinue

    if ($mysql) {
        & $mysql.Source --version
    }
    else {
        $fallback = 'C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysql.exe'

        if (Test-Path $fallback) {
            & $fallback --version
        }
        else {
            throw 'Khong tim thay mysql.exe.'
        }
    }
}

Write-Check 'Project files' {
    $paths = @(
        (Join-Path $Laravel '.env'),
        (Join-Path $Laravel 'artisan'),
        (Join-Path $Laravel 'composer.json'),
        (Join-Path $Laravel 'database\migrations'),
        (Join-Path $Laravel 'database\seeders'),
        (Join-Path $Root '03_Database')
    )

    foreach ($path in $paths) {
        if (Test-Path $path) {
            Write-Host "[OK] $path" -ForegroundColor Green
        }
        else {
            Write-Host "[THIEU] $path" -ForegroundColor Yellow
        }
    }
}

Write-Host "`n===== HOAN TAT KIEM TRA =====" -ForegroundColor Green
Write-Host 'Script khong sua file va khong thay doi database.'
