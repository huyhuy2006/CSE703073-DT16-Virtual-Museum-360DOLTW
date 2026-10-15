$ErrorActionPreference = 'Stop'

. "$PSScriptRoot\lib-env.ps1"

$config = Get-DbConfig

# Chỉ cho phép xuất schema từ hai database phát triển đã biết.
$allowedDatabases = @(
    'baotang360',
    'baotang360_restore_test'
)

if ($config.Name -notin $allowedDatabases) {
    throw "Database khong nam trong danh sach cho phep: $($config.Name)"
}

$dumpCommand = Get-Command mysqldump -ErrorAction SilentlyContinue

if ($dumpCommand) {
    $dumpExe = $dumpCommand.Source
}
else {
    $fallback = 'C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysqldump.exe'

    if (-not (Test-Path $fallback)) {
        throw 'Khong tim thay mysqldump.exe.'
    }

    $dumpExe = $fallback
}

New-Item -ItemType Directory -Path $script:DbDir -Force |
    Out-Null

$schemaPath = Join-Path $script:DbDir 'schema.sql'
$dictionaryPath = Join-Path $script:DbDir 'data-dictionary.tsv'

$oldMysqlPwd = $env:MYSQL_PWD

try {
    if ($config.Pass) {
        $env:MYSQL_PWD = $config.Pass
    }
    else {
        Remove-Item Env:\MYSQL_PWD -ErrorAction SilentlyContinue
    }

    Write-Host "Dang xuat schema cua database: $($config.Name)" `
        -ForegroundColor Cyan

    & $dumpExe `
        '--host' $config.Host `
        '--port' $config.Port `
        '--user' $config.User `
        '--no-data' `
        '--skip-comments' `
        '--no-tablespaces' `
        '--default-character-set=utf8mb4' `
        "--result-file=$schemaPath" `
        $config.Name

    if ($LASTEXITCODE -ne 0) {
        throw 'mysqldump that bai.'
    }
}
finally {
    if ($null -ne $oldMysqlPwd) {
        $env:MYSQL_PWD = $oldMysqlPwd
    }
    else {
        Remove-Item Env:\MYSQL_PWD -ErrorAction SilentlyContinue
    }
}

# Xuất từ điển dữ liệu từ information_schema.
$sql = @"
SELECT
    TABLE_NAME,
    ORDINAL_POSITION,
    COLUMN_NAME,
    COLUMN_TYPE,
    IS_NULLABLE,
    IFNULL(COLUMN_DEFAULT, ''),
    COLUMN_KEY,
    IFNULL(COLUMN_COMMENT, '')
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = '$($config.Name)'
ORDER BY TABLE_NAME, ORDINAL_POSITION;
"@

$dictionary = Invoke-MySqlReadOnly `
    -Sql $sql `
    -User $config.User `
    -Pass $config.Pass `
    -HostName $config.Host `
    -Port $config.Port

$header = "Bang`tThuTu`tTruong`tKieu`tChoPhepNull`tMacDinh`tKhoa`tY Nghia Nghiep Vu"

$content = @($header) + @($dictionary)

[System.IO.File]::WriteAllLines(
    $dictionaryPath,
    $content,
    [System.Text.UTF8Encoding]::new($true)
)

Write-Host "`nDa xuat:" -ForegroundColor Green
Write-Host $schemaPath
Write-Host $dictionaryPath
Write-Host "Chi xuat cau truc; khong xuat du lieu cac ban ghi."
