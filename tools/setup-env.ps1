# tools\setup-env.ps1
# Chuan hoa moi truong CSE703073 - DT-16 tren Windows.
# Script khong tu dong thay doi BIOS hoac mat khau MySQL.

[CmdletBinding()]
param(
    [string]$GitName = "Wessel Peter Huy",
    [string]$GitEmail = ""
)

$ErrorActionPreference = "Continue"

function Write-Step {
    param([string]$Message)

    Write-Host ""
    Write-Host "===== $Message =====" -ForegroundColor Cyan
}

function Test-Command {
    param(
        [string]$Name,
        [string]$Arguments
    )

    $command = Get-Command $Name -ErrorAction SilentlyContinue

    if (-not $command) {
        Write-Host "[THIEU] $Name" -ForegroundColor Yellow
        return
    }

    Write-Host "[TIM THAY] $Name -> $($command.Source)" -ForegroundColor Green
    Write-Host "  Lenh kiem tra: $Name $Arguments"
}

Write-Step "1. Windows va phan cung"

try {
    $os = Get-CimInstance Win32_OperatingSystem
    $computer = Get-CimInstance Win32_ComputerSystem

    Write-Host ("Windows: {0}" -f $os.Caption)
    Write-Host (
        "RAM: {0:N1} GB" -f
        ($computer.TotalPhysicalMemory / 1GB)
    )

    Write-Host (
        "O C con trong: {0:N1} GB" -f
        ((Get-PSDrive C).Free / 1GB)
    )

    $cpu = Get-CimInstance Win32_Processor |
        Select-Object -First 1

    Write-Host (
        "Ao hoa firmware: {0}" -f
        $cpu.VirtualizationFirmwareEnabled
    )
}
catch {
    Write-Host "Khong doc duoc day du thong tin phan cung." -ForegroundColor Yellow
}

Write-Step "2. Kiem tra cong cu"

Test-Command "git" "--version"
Test-Command "code" "--version"
Test-Command "php" "-v"
Test-Command "composer" "--version"
Test-Command "mysql" "--version"
Test-Command "node" "--version"
Test-Command "npm" "--version"
Test-Command "py" "-3.12 --version"
Test-Command "docker" "--version"
Test-Command "wsl" "--status"

Write-Step "3. Kiem tra PHP extensions"

if (Get-Command php -ErrorAction SilentlyContinue) {
    $modules = php -m

    $extensions = @(
        "pdo_mysql",
        "mbstring",
        "openssl",
        "fileinfo",
        "curl",
        "zip",
        "intl",
        "gd"
    )

    foreach ($extension in $extensions) {
        if ($modules -contains $extension) {
            Write-Host "[OK] $extension" -ForegroundColor Green
        }
        else {
            Write-Host "[THIEU] $extension" -ForegroundColor Yellow
        }
    }
}
else {
    Write-Host "Bo qua: chua co PHP trong PATH." -ForegroundColor Yellow
}

Write-Step "4. Cau hinh danh tinh Git"

if (Get-Command git -ErrorAction SilentlyContinue) {
    if ([string]::IsNullOrWhiteSpace($GitEmail)) {
        $GitEmail = Read-Host "Nhap email GitHub/GitLab that cua ban"
    }

    if ([string]::IsNullOrWhiteSpace($GitEmail)) {
        Write-Host "Chua nhap email. Bo qua cau hinh email." -ForegroundColor Yellow
    }
    else {
        git config --global user.name $GitName
        git config --global user.email $GitEmail
        git config --global init.defaultBranch main
        git config --global core.autocrlf false
        git config --global pull.rebase false

        Write-Host ("user.name : {0}" -f (git config --global user.name))
        Write-Host ("user.email: {0}" -f (git config --global user.email))
    }
}
else {
    Write-Host "Chua co Git, bo qua cau hinh danh tinh." -ForegroundColor Yellow
}

Write-Step "5. Kiem tra MySQL local"

if (Get-Command mysql -ErrorAction SilentlyContinue) {
    $port = Test-NetConnection -ComputerName localhost -Port 3306 `
        -WarningAction SilentlyContinue

    if ($port.TcpTestSucceeded) {
        Write-Host "[OK] Co dich vu phan hoi tai cong 3306." -ForegroundColor Green
        Write-Host "Can kiem tra dang nhap MySQL rieng o buoc nghiem thu."
    }
    else {
        Write-Host "[CHUA CHAY] MySQL chua phan hoi tai cong 3306." -ForegroundColor Yellow
        Write-Host "Neu dung Laragon, mo Laragon va bam Start All."
    }
}
else {
    Write-Host "Chua co MySQL client trong PATH." -ForegroundColor Yellow
}

Write-Step "6. Hoan tat"

Write-Host "Script chi kiem tra va cau hinh Git; khong tu dong cai phan mem."
Write-Host "Neu co [THIEU], hay cai cong cu do roi chay tools\doctor.ps1."
