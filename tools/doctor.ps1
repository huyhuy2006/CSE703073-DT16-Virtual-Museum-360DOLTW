$ErrorActionPreference = "Continue"

$root = "C:\CSE703073\BaoTang360"
$laravel = Join-Path $root "02_SourceCode\laravel"

Set-Location $root

$failures = [System.Collections.Generic.List[string]]::new()

function Check-File {
    param([string]$Path)

    if (Test-Path $Path -PathType Leaf) {
        Write-Host "[OK] $Path" -ForegroundColor Green
    }
    else {
        Write-Host "[MISSING] $Path" -ForegroundColor Red
        $script:failures.Add("Missing file: $Path")
    }
}

function Check-Command {
    param([string]$Name)

    if (Get-Command $Name -ErrorAction SilentlyContinue) {
        Write-Host "[OK] Command: $Name" -ForegroundColor Green
    }
    else {
        Write-Host "[MISSING] Command: $Name" -ForegroundColor Red
        $script:failures.Add("Missing command: $Name")
    }
}

Write-Host "===== PROJECT CONFIGURATION =====" -ForegroundColor Cyan

@(
    ".gitignore",
    ".gitattributes",
    ".editorconfig",
    ".env.example",
    "README.md",
    ".githooks\commit-msg",
    ".githooks\commit-msg.ps1",
    ".vscode\extensions.json",
    "tools\setup-hooks.ps1",
    "tools\git-stats.ps1"
) | ForEach-Object {
    Check-File (Join-Path $root $_)
}

Write-Host ""
Write-Host "===== REQUIRED TOOLS =====" -ForegroundColor Cyan

@("git", "php", "composer", "node", "npm", "py") |
    ForEach-Object { Check-Command $_ }

Write-Host ""
Write-Host "===== LARAVEL APPLICATION =====" -ForegroundColor Cyan

Check-File (Join-Path $laravel "artisan")
Check-File (Join-Path $laravel "composer.json")
Check-File (Join-Path $laravel "package.json")

if (Test-Path (Join-Path $laravel "artisan")) {
    Push-Location $laravel

    php artisan --version

    if ($LASTEXITCODE -ne 0) {
        $failures.Add("Laravel artisan command failed.")
    }

    Pop-Location
}

Write-Host ""
Write-Host "===== GIT CONFIGURATION =====" -ForegroundColor Cyan

git status --short --branch
git config --get core.hooksPath

Write-Host ""
Write-Host "===== RESULT =====" -ForegroundColor Cyan

if ($failures.Count -eq 0) {
    Write-Host "Basic checks passed." -ForegroundColor Green
    exit 0
}

Write-Host ("Checks requiring attention: {0}" -f $failures.Count) -ForegroundColor Yellow

$failures | ForEach-Object {
    Write-Host "- $_"
}

exit 1
