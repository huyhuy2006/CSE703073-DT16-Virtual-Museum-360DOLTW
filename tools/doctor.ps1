# tools\doctor.ps1
# Kiem chung moi truong phat trien CSE703073 - DT-16 tren Windows.

$ErrorActionPreference = "Continue"

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host " KIEM CHUNG MOI TRUONG BAO TANG 360 DO" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan

$os = Get-CimInstance Win32_OperatingSystem
$computer = Get-CimInstance Win32_ComputerSystem
$cpu = Get-CimInstance Win32_Processor | Select-Object -First 1

Write-Host ("Windows: {0}" -f $os.Caption)
Write-Host ("RAM: {0:N1} GB" -f ($computer.TotalPhysicalMemory / 1GB))
Write-Host ("O C con trong: {0:N1} GB" -f ((Get-PSDrive C).Free / 1GB))
Write-Host ("ExecutionPolicy: {0}" -f (Get-ExecutionPolicy -Scope CurrentUser))
Write-Host ("VirtualizationFirmwareEnabled: {0}" -f $cpu.VirtualizationFirmwareEnabled)

$checks = @(
    @{ Name = "Git";       Exe = "git";      Args = @("--version");          Required = "Git >= 2.40" },
    @{ Name = "PHP";       Exe = "php";      Args = @("-v");                 Required = "PHP 8.3" },
    @{ Name = "Composer";  Exe = "composer"; Args = @("--version");           Required = "Composer >= 2.7" },
    @{ Name = "MySQL";     Exe = "mysql";    Args = @("--version");           Required = "MySQL >= 8.0" },
    @{ Name = "Node.js";   Exe = "node";     Args = @("--version");           Required = "Node.js >= 20" },
    @{ Name = "npm";       Exe = "npm";      Args = @("--version");           Required = "npm >= 10" },
    @{ Name = "Python";    Exe = "py";       Args = @("-3.12", "--version");  Required = "Python 3.12" },
    @{ Name = "VS Code";   Exe = "code";     Args = @("--version");           Required = "Visual Studio Code" },
    @{ Name = "Docker";    Exe = "docker";   Args = @("--version");           Required = "Docker Desktop" },
    @{ Name = "WSL";       Exe = "wsl";      Args = @("--version");           Required = "WSL2 neu dung Docker" }
)

Write-Host ""
Write-Host "--- CONG CU ---" -ForegroundColor Cyan

foreach ($check in $checks) {
    $cmd = Get-Command $check.Exe -ErrorAction SilentlyContinue

    if (-not $cmd) {
        Write-Host (
            "[THIEU] {0} - Can: {1}" -f $check.Name, $check.Required
        ) -ForegroundColor Yellow

        continue
    }

    try {
        $output = & $check.Exe @($check.Args) 2>&1
        $exitCode = $LASTEXITCODE

        if ($exitCode -eq 0) {
            $firstLine = $output | Select-Object -First 1
            Write-Host (
                "[OK] {0}: {1}" -f $check.Name, $firstLine
            ) -ForegroundColor Green
        }
        else {
            Write-Host (
                "[CAN KIEM TRA] {0}" -f $check.Name
            ) -ForegroundColor Yellow
        }
    }
    catch {
        Write-Host (
            "[LOI] Khong chay duoc {0}" -f $check.Name
        ) -ForegroundColor Yellow
    }
}

Write-Host ""
Write-Host "--- PHP EXTENSIONS ---" -ForegroundColor Cyan

if (Get-Command php -ErrorAction SilentlyContinue) {
    $modules = php -m

    foreach ($extension in @(
        "pdo_mysql",
        "mbstring",
        "openssl",
        "fileinfo",
        "curl",
        "zip",
        "intl",
        "gd"
    )) {
        if ($modules -contains $extension) {
            Write-Host "[OK] $extension" -ForegroundColor Green
        }
        else {
            Write-Host "[THIEU] $extension" -ForegroundColor Yellow
        }
    }
}
else {
    Write-Host "[BO QUA] Chua co PHP." -ForegroundColor Yellow
}

Write-Host ""
Write-Host "--- MYSQL SERVER ---" -ForegroundColor Cyan

$mysqlPort = Test-NetConnection -ComputerName localhost -Port 3306 `
    -WarningAction SilentlyContinue

if ($mysqlPort.TcpTestSucceeded) {
    Write-Host "[OK] Co dich vu phan hoi tai cong 3306." -ForegroundColor Green
    Write-Host "Hay kiem tra dang nhap va SELECT VERSION() o buoc nghiem thu."
}
else {
    Write-Host "[THIEU] Chua co dich vu MySQL tai cong 3306." -ForegroundColor Yellow
    Write-Host "Neu dung Laragon, mo Laragon va bam Start All."
}

Write-Host ""
Write-Host "--- GIT IDENTITY ---" -ForegroundColor Cyan

if (Get-Command git -ErrorAction SilentlyContinue) {
    $gitName = git config --global user.name
    $gitEmail = git config --global user.email

    Write-Host ("user.name : {0}" -f $gitName)
    Write-Host ("user.email: {0}" -f $gitEmail)

    if ([string]::IsNullOrWhiteSpace($gitName)) {
        Write-Host "[THIEU] Can cau hinh git user.name." -ForegroundColor Yellow
    }

    if ([string]::IsNullOrWhiteSpace($gitEmail)) {
        Write-Host "[THIEU] Can cau hinh git user.email." -ForegroundColor Yellow
    }
}

Write-Host ""
Write-Host "--- DOCKER ENGINE ---" -ForegroundColor Cyan

if (Get-Command docker -ErrorAction SilentlyContinue) {
    docker info --format "{{.ServerVersion}}" 2>$null

    if ($LASTEXITCODE -eq 0) {
        Write-Host "[OK] Docker engine dang chay." -ForegroundColor Green
    }
    else {
        Write-Host "[CHUA CHAY] Mo Docker Desktop va cho khoi dong." -ForegroundColor Yellow
    }
}
else {
    Write-Host "[BO QUA] Chua co Docker." -ForegroundColor Yellow
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host " KET THUC KIEM CHUNG" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
