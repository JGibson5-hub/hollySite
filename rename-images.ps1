# Function to clean filename
function Clean-FileName {
    param([string]$fileName)
    
    # Remove dimensions (e.g., 2025x900)
    $cleaned = $fileName -replace '\s+\d+x\d+', ''
    
    # Replace spaces with underscores
    $cleaned = $cleaned -replace '\s+', '_'
    
    # Remove any numbers at the end before extension (e.g., Logo5.jpg -> Logo.jpg)
    $cleaned = $cleaned -replace '(\d+)(?=\.[^.]+$)', ''
    
    return $cleaned
}

# Directories to process
$directories = @(
    "images/gallery/optimized/thumbs",
    "images/gallery/optimized/full"
)

foreach ($dir in $directories) {
    if (Test-Path $dir) {
        Get-ChildItem $dir -File | ForEach-Object {
            $newName = Clean-FileName $_.Name
            if ($_.Name -ne $newName) {
                Write-Host "Renaming $($_.Name) to $newName"
                Rename-Item -Path $_.FullName -NewName $newName -Force
            }
        }
    }
}

Write-Host "File renaming complete!" 