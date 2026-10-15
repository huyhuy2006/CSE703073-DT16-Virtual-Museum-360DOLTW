$ErrorActionPreference = "Stop"

$rootResult = git rev-parse --show-toplevel

if ($LASTEXITCODE -ne 0 -or [string]::IsNullOrWhiteSpace($rootResult)) {
    throw "Current directory is not a Git repository."
}

$root = $rootResult.Trim()
Set-Location $root

$hook = Join-Path $root ".githooks\commit-msg"
$hookScript = Join-Path $root ".githooks\commit-msg.ps1"

if (-not (Test-Path $hook -PathType Leaf)) {
    throw "Missing .githooks\commit-msg"
}

if (-not (Test-Path $hookScript -PathType Leaf)) {
    throw "Missing .githooks\commit-msg.ps1"
}

git config core.hooksPath .githooks

if ($LASTEXITCODE -ne 0) {
    throw "Could not configure Git hooks."
}

Write-Host "core.hooksPath = $(git config core.hooksPath)" -ForegroundColor Green

function Test-CommitMessage {
    param(
        [string]$Message,
        [int]$ExpectedExitCode
    )

    $tempFile = Join-Path $env:TEMP ("commit-msg-" + [guid]::NewGuid() + ".txt")

    try {
        [System.IO.File]::WriteAllText(
            $tempFile,
            $Message,
            [System.Text.UTF8Encoding]::new($false)
        )

        & powershell.exe -NoProfile -ExecutionPolicy Bypass -File $hookScript $tempFile
        $actualExitCode = $LASTEXITCODE

        if ($actualExitCode -eq $ExpectedExitCode) {
            Write-Host "[OK] $Message" -ForegroundColor Green
            return $true
        }

        Write-Host "[FAIL] $Message (actual=$actualExitCode, expected=$ExpectedExitCode)" -ForegroundColor Red
        return $false
    }
    finally {
        Remove-Item $tempFile -Force -ErrorAction SilentlyContinue
    }
}

$tests = @(
    @{ Message = "feat(viewer): add museum scene navigation"; Expected = 0 },
    @{ Message = "chore: initialize project configuration"; Expected = 0 },
    @{ Message = "bad message"; Expected = 1 },
    @{ Message = "Feat: uppercase type must fail"; Expected = 1 }
)

$allPassed = $true

foreach ($test in $tests) {
    if (-not (Test-CommitMessage -Message $test.Message -ExpectedExitCode $test.Expected)) {
        $allPassed = $false
    }
}

if (-not $allPassed) {
    throw "Commit hook tests failed."
}

Write-Host "Commit hook is configured and tested." -ForegroundColor Green
