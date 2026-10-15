# tools\doctor.ps1
# Kiem tra moi truong phat trien du an CSE703073 - DT-16
# Chi kiem tra, khong tu dong cai dat hoac thay doi cau hinh.

$ErrorActionPreference = "Continue"

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host " KIEM TRA MOI TRUONG BAO TANG 360 DO" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan

try {
    $os = Get-CimInstance Win32_OperatingSystem
    Write-Host ("Windows: {0}" -f $os.Caption)
}
catch {
    Write-Host "Khong doc duoc thong tin Windows." -ForegroundColor Yellow
}

Write-Host ("ExecutionPolicy: {0}" -f (Get-ExecutionPolicy -Scope CurrentUser))

$checks = @(
    @{
        Name = "Git"
        Exe = "git"
        Args = @("--version")
        Required = "Can cai Git"
    },
    @{
        Name = "PHP"
        Exe = "php"
        Args = @("-v")
        Required = "Can PHP 8.3"
    },
    @{
        Name = "Composer"
        Exe = "composer"
        Args = @("--version")
        Required = "Can Composer"
    },
    @{
        Name = "MySQL"
        Exe = "mysql"
        Args = @("--version")
        Required = "Can MySQL client"
    },
    @{
        Name = "Node.js"
        Exe = "node"
        Args = @("--version")
        Required = "Can Node.js LTS"
    },
    @{
        Name = "npm"
        Exe = "npm"
        Args = @("--version")
        Required = "Can npm"
    },
    @{
        Name = "Python 3.12"
        Exe = "py"
        Args = @("-3.12", "--version")
        Required = "Can Python 3.12"
    },
    @{
        Name = "VS Code"
        Exe = "code"
        Args = @("--version")
        Required = "Can VS Code"
    }
)

Write-Host ""
Write-Host "--- KIEM TRA CONG CU ---" -ForegroundColor Cyan

foreach ($check in $checks) {
    $cmd = Get-Command $check.Exe -ErrorAction SilentlyContinue

    if ($cmd) {
        try {
            $result = & $check.Exe @($check.Args) 2>&1 |
                Select-Object -First 1

            if ($LASTEXITCODE -eq 0) {
                Write-Host (
                    "[OK] {0,-12} {1}" -f $check.Name, $result
                ) -ForegroundColor Green
            }
            else {
                Write-Host (
                    "[LOI] {0,-12} {1}" -f $check.Name, $result
                ) -ForegroundColor Yellow
            }
        }
        catch {
            Write-Host (
                "[LOI] {0,-12} Khong chay duoc lenh." -f $check.Name
            ) -ForegroundColor Yellow
        }
    }
    else {
        Write-Host (
            "[THIEU] {0,-12} {1}" -f $check.Name, $check.Required
        ) -ForegroundColor Yellow
    }
}

Write-Host ""
Write-Host "--- KIEM TRA PHP EXTENSIONS ---" -ForegroundColor Cyan

if (Get-Command php -ErrorAction SilentlyContinue) {
    $phpModules = php -m

    $requiredExtensions = @(
        "pdo_mysql",
        "mbstring",
        "openssl",
        "fileinfo",
        "curl",
        "zip",
        "intl",
        "gd"
    )

    foreach ($extension in $requiredExtensions) {
        if ($phpModules -contains $extension) {
            Write-Host ("[OK] {0}" -f $extension) -ForegroundColor Green
        }
        else {
            Write-Host (
                "[THIEU] {0} - kiem tra php.ini" -f $extension
            ) -ForegroundColor Yellow
        }
    }
}
else {
    Write-Host "Bo qua extension: chua co lenh php." -ForegroundColor Yellow
}

Write-Host ""
Write-Host "--- KIEM TRA GIT CA NHAN ---" -ForegroundColor Cyan

if (Get-Command git -ErrorAction SilentlyContinue) {
    $gitName = git config --global user.name
    $gitEmail = git config --global user.email

    Write-Host ("user.name : {0}" -f $gitName)
    Write-Host ("user.email: {0}" -f $gitEmail)

    if ([string]::IsNullOrWhiteSpace($gitName)) {
        Write-Host "Can cau hinh git user.name." -ForegroundColor Yellow
    }

    if ([string]::IsNullOrWhiteSpace($gitEmail)) {
        Write-Host "Can cau hinh git user.email." -ForegroundColor Yellow
    }
}
else {
    Write-Host "Chua the kiem tra Git." -ForegroundColor Yellow
}

Write-Host ""
Write-Host "--- KIEM TRA O C VA AO HOA ---" -ForegroundColor Cyan

try {
    $drive = Get-PSDrive C
    Write-Host (
        "Dung luong trong o C: {0} GB" -f
        [math]::Round($drive.Free / 1GB, 1)
    )
}
catch {
    Write-Host "Khong doc duoc dung luong o C." -ForegroundColor Yellow
}

try {
    $cpu = Get-CimInstance Win32_Processor |
        Select-Object -First 1

    Write-Host (
        "VirtualizationFirmwareEnabled: {0}" -f
        $cpu.VirtualizationFirmwareEnabled
    )
}
catch {
    Write-Host "Khong doc duoc trang thai ao hoa." -ForegroundColor Yellow
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host " HOAN TAT KIEM TRA" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
