@echo off
setlocal enabledelayedexpansion

REM Create directories for optimized images
mkdir "images\optimized" 2>nul
mkdir "images\gallery\optimized" 2>nul
mkdir "images\gallery\optimized\thumbs" 2>nul
mkdir "images\gallery\optimized\full" 2>nul

REM Optimize main images
magick "images\Imaginuitive_Logo_Clr.png" -strip -quality 85 "images\optimized\Imaginuitive_Logo_Clr.webp"
magick "images\Imaginuitive_Logo_Clr.png" -strip -quality 85 "images\optimized\Imaginuitive_Logo_Clr.png"
magick "images\HollyPhoto.jpg" -strip -quality 85 "images\optimized\HollyPhoto.webp"
magick "images\HollyPhoto.jpg" -strip -quality 85 "images\optimized\HollyPhoto.jpg"

REM Process gallery images
for %%f in ("images\gallery\*Full*.jpg") do (
    REM Create WebP version of full image (max 1MB)
    magick "%%f" -strip -resize "1920x1080>" -quality 85 -define webp:target-size=1048576 "images\gallery\optimized\full\%%~nf.webp"
    REM Create JPG fallback of full image
    magick "%%f" -strip -resize "1920x1080>" -quality 85 "images\gallery\optimized\full\%%~nf.jpg"
)

REM Process thumbnails
for %%f in ("images\gallery\*Thumb*.jpg") do (
    REM Create standard size thumbnails (450x450 for square, 675x900 for vertical)
    if "%%f"=="*SQ*" (
        REM Square thumbnails
        magick "%%f" -strip -resize "450x450^" -gravity center -extent 450x450 -quality 85 "images\gallery\optimized\thumbs\%%~nf.webp"
        magick "%%f" -strip -resize "450x450^" -gravity center -extent 450x450 -quality 85 "images\gallery\optimized\thumbs\%%~nf.jpg"
    ) else (
        REM Regular thumbnails
        magick "%%f" -strip -resize "675x900>" -quality 85 "images\gallery\optimized\thumbs\%%~nf.webp"
        magick "%%f" -strip -resize "675x900>" -quality 85 "images\gallery\optimized\thumbs\%%~nf.jpg"
    )
)

echo Image optimization complete!
echo New optimized images are in the 'optimized' folders.
pause 