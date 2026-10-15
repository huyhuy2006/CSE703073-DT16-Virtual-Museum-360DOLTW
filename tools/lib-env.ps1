$ErrorActionPreference = 'Stop'

$script:Root = Split-Path $PSScriptRoot -Parent
$script:Laravel = Join-Path $script:Root '02_SourceCode\laravel'
$script:DbDir = Join-Path $script:Root '03_Database'

function Get-EnvValue {
    param(
        [Parameter(Mandatory = $true)]
        [string]$Name
    )

    $envPath = Join-Path $script:Laravel '.env'

    if (-not (Test-Path $envPath)) {
        throw "Khong tim thay file .env: $envPath"
    }

    $line = Get-Content $envPath |
        Where-Object {
            $_ -match "^\s*$([regex]::Escape($Name))\s*="
        } |
        Select-Object -First 1

    if (-not $line) {
        return ''
    }

    $value = ($line -split '=', 2)[1].Trim()

    # Loại bỏ cặp dấu nháy bao quanh nếu có
    if (
        $value.Length -ge 2 -and
        (
            ($value.StartsWith('"') -and $value.EndsWith('"')) -or
            ($value.StartsWith("'") -and $value.EndsWith("'"))
        )
    ) {
        $value = $value.Substring(1, $value.Length - 2)
    }

    return $value
}

function Get-DbConfig {
    $config = @{
        Name = Get-EnvValue 'DB_DATABASE'
        User = Get-EnvValue 'DB_USERNAME'
        Pass = Get-EnvValue 'DB_PASSWORD'
        Host = Get-EnvValue 'DB_HOST'
        Port = Get-EnvValue 'DB_PORT'
    }

    if (-not $config.Host) {
        $config.Host = '127.0.0.1'
    }

    if (-not $config.Port) {
        $config.Port = '3306'
    }

    if (-not $config.Name) {
        throw 'DB_DATABASE dang de trong trong .env.'
    }

    if (-not $config.User) {
        throw 'DB_USERNAME dang de trong trong .env.'
    }

    return $config
}

function Get-MySqlExecutable {
    $command = Get-Command mysql -ErrorAction SilentlyContinue

    if ($command) {
        return $command.Source
    }

    $fallback = 'C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysql.exe'

    if (Test-Path $fallback) {
        return $fallback
    }

    throw 'Khong tim thay mysql.exe trong PATH hoac thu muc Laragon.'
}

function Invoke-MySqlReadOnly {
    param(
        [Parameter(Mandatory = $true)]
        [string]$Sql,

        [string]$Database = '',

        [Parameter(Mandatory = $true)]
        [string]$User,

        [string]$Pass = '',

        [string]$HostName = '127.0.0.1',

        [string]$Port = '3306'
    )

    # Chỉ chấp nhận một nhóm lệnh truy vấn để tránh chạy nhầm
    # các câu lệnh thay đổi dữ liệu qua hàm này.
    $trimmedSql = $Sql.Trim()

    if ($trimmedSql -notmatch '^(SELECT|SHOW|DESCRIBE|DESC|EXPLAIN)\b') {
        throw 'Ham nay chi cho phep SELECT, SHOW, DESCRIBE hoac EXPLAIN.'
    }

    $mysqlExe = Get-MySqlExecutable

    $oldMysqlPwd = $env:MYSQL_PWD

    try {
        if ($Pass) {
            $env:MYSQL_PWD = $Pass
        }
        else {
            Remove-Item Env:\MYSQL_PWD -ErrorAction SilentlyContinue
        }

        $arguments = @(
            '--host', $HostName,
            '--port', $Port,
            '--user', $User,
            '--default-character-set=utf8mb4',
            '--batch',
            '--raw'
        )

        if ($Database) {
            $arguments += @('--database', $Database)
        }

        $arguments += @('--execute', $trimmedSql)

        $output = & $mysqlExe @arguments 2>&1
        $exitCode = $LASTEXITCODE

        if ($exitCode -ne 0) {
            throw "MySQL truy van that bai: $($output | Out-String)"
        }

        return $output
    }
    finally {
        if ($null -ne $oldMysqlPwd) {
            $env:MYSQL_PWD = $oldMysqlPwd
        }
        else {
            Remove-Item Env:\MYSQL_PWD -ErrorAction SilentlyContinue
        }
    }
}
