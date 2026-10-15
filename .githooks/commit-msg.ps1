param(
    [Parameter(Mandatory = $true)]
    [string]$MessageFile
)

if (-not (Test-Path -LiteralPath $MessageFile -PathType Leaf)) {
    [Console]::Error.WriteLine("Commit message file was not found.")
    exit 1
}

$first = Get-Content -LiteralPath $MessageFile -TotalCount 1 -Encoding UTF8

if ($null -eq $first) {
    $first = ""
}

$first = $first.TrimStart([char]0xFEFF).Trim()

if ($first -match '^(Merge|Revert) ') {
    exit 0
}

$pattern = '^(feat|fix|docs|refactor|test|chore|perf)(\([a-z0-9._-]+\))?: .{10,}$'

if ($first -cmatch $pattern) {
    exit 0
}

[Console]::Error.WriteLine("Invalid commit message.")
[Console]::Error.WriteLine("Use: type(scope): meaningful description")
[Console]::Error.WriteLine("Types: feat, fix, docs, refactor, test, chore, perf")
[Console]::Error.WriteLine("Example: feat(viewer): add museum scene navigation")

exit 1
