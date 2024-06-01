param (
    [string]$GITHUB_TOKEN,
    [string]$NEW_VERSION,
    [string]$REPO
)

$headers = @{ Authorization = "token $GITHUB_TOKEN" }
$body = @{
    tag_name         = $NEW_VERSION
    target_commitish = "main"
    name             = "Release $NEW_VERSION"
    body             = "Description of the release"
    draft            = $false
    prerelease       = $false
} | ConvertTo-Json -Compress

Invoke-RestMethod -Uri "https://api.github.com/repos/$REPO/releases" -Method Post -Headers $headers -Body $body -ContentType "application/json"
