param(
    [string]$Author = ""
)

$ErrorActionPreference = "Continue"

$root = git rev-parse --show-toplevel

if ($LASTEXITCODE -ne 0) {
    throw "Current directory is not a Git repository."
}

Set-Location $root.Trim()

Write-Host "===== COMMIT COUNT BY AUTHOR =====" -ForegroundColor Cyan

$authors = @(git log --all --format="%aN <%aE>")

if ($authors.Count -eq 0) {
    Write-Host "No commits found."
    return
}

$total = $authors.Count

$authors |
    Group-Object |
    Sort-Object Count -Descending |
    ForEach-Object {
        $percent = [math]::Round(100 * $_.Count / $total, 1)

        Write-Host (
            "{0,4} commits | {1,5}% | {2}" -f
            $_.Count,
            $percent,
            $_.Name
        )
    }

Write-Host "Total commits: $total"

if (-not [string]::IsNullOrWhiteSpace($Author)) {
    Write-Host ""
    Write-Host "===== LINE CHANGE SUMMARY: $Author =====" -ForegroundColor Cyan

    $added = 0
    $deleted = 0

    git log --all --pretty=tformat: --numstat --author="$Author" |
        ForEach-Object {
            $parts = $_ -split "`t"

            if (
                $parts.Count -ge 3 -and
                $parts[0] -match '^\d+$' -and
                $parts[1] -match '^\d+$'
            ) {
                $added += [int]$parts[0]
                $deleted += [int]$parts[1]
            }
        }

    Write-Host "Lines added: $added"
    Write-Host "Lines deleted: $deleted"
}

Write-Host ""
Write-Host "===== COMMITS BY WEEK =====" -ForegroundColor Cyan

$weekEntries = @(
    git log --all --date=format:%Y-W%V --pretty=format:%ad |
        Group-Object |
        Sort-Object Name
)

foreach ($week in $weekEntries) {
    Write-Host ("{0}: {1} commits" -f $week.Name, $week.Count)
}

Write-Host ("Weeks with commits: {0}" -f $weekEntries.Count)

Write-Host ""
Write-Host "===== CHECK .ENV IN HISTORY =====" -ForegroundColor Cyan

$envFiles = @(
    git log --all --name-only --pretty=format: |
        Where-Object { $_ -match '(^|/)\.env$' } |
        Sort-Object -Unique
)

if ($envFiles.Count -eq 0) {
    Write-Host "[OK] No .env file found in Git history." -ForegroundColor Green
}
else {
    Write-Host "[WARNING] .env appears in Git history." -ForegroundColor Red
    $envFiles | ForEach-Object { Write-Host $_ }
}
