<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ImageService
{
    /**
     * Upload and automatically resize/compress an image to given dimensions (e.g. 400x400 square).
     *
     * @param UploadedFile $file
     * @param string $folder e.g. 'categories', 'subcategories', 'cities'
     * @param int $targetWidth
     * @param int $targetHeight
     * @return string Relative URL path (e.g. 'uploads/categories/abc12345.webp')
     */
    public static function uploadAndResize(UploadedFile $file, string $folder = 'categories', int $targetWidth = 400, int $targetHeight = 400): string
    {
        $targetDir = public_path("uploads/{$folder}");
        File::ensureDirectoryExists($targetDir);

        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . time() . '_' . Str::random(6);

        // If GD is available, perform high-quality square resize and compression
        if (extension_loaded('gd')) {
            try {
                $sourcePath = $file->getRealPath();
                $imageInfo = @getimagesize($sourcePath);

                if ($imageInfo) {
                    $mime = $imageInfo['mime'];
                    $srcW = $imageInfo[0];
                    $srcH = $imageInfo[1];

                    $srcImg = match ($mime) {
                        'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($sourcePath),
                        'image/png' => @imagecreatefrompng($sourcePath),
                        'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($sourcePath) : null,
                        default => null,
                    };

                    if ($srcImg) {
                        // Calculate aspect ratio crop (center crop)
                        $srcAspect = $srcW / $srcH;
                        $targetAspect = $targetWidth / $targetHeight;

                        if ($srcAspect >= $targetAspect) {
                            // Source is wider: crop width
                            $cropH = $srcH;
                            $cropW = (int) ($srcH * $targetAspect);
                            $srcX = (int) (($srcW - $cropW) / 2);
                            $srcY = 0;
                        } else {
                            // Source is taller: crop height
                            $cropW = $srcW;
                            $cropH = (int) ($srcW / $targetAspect);
                            $srcX = 0;
                            $srcY = (int) (($srcH - $cropH) / 2);
                        }

                        $dstImg = imagecreatetruecolor($targetWidth, $targetHeight);

                        // Preserve transparency for PNG / WEBP
                        if ($mime === 'image/png' || $mime === 'image/webp') {
                            imagealphablending($dstImg, false);
                            imagesavealpha($dstImg, true);
                            $transparent = imagecolorallocatealpha($dstImg, 255, 255, 255, 127);
                            imagefilledrectangle($dstImg, 0, 0, $targetWidth, $targetHeight, $transparent);
                        } else {
                            $white = imagecolorallocate($dstImg, 255, 255, 255);
                            imagefilledrectangle($dstImg, 0, 0, $targetWidth, $targetHeight, $white);
                        }

                        imagecopyresampled($dstImg, $srcImg, 0, 0, $srcX, $srcY, $targetWidth, $targetHeight, $cropW, $cropH);

                        // Save as webp if supported, otherwise jpeg
                        if (function_exists('imagewebp')) {
                            $destFile = "{$targetDir}/{$filename}.webp";
                            imagewebp($dstImg, $destFile, 85);
                            $relativePath = "uploads/{$folder}/{$filename}.webp";
                        } else {
                            $destFile = "{$targetDir}/{$filename}.jpg";
                            imagejpeg($dstImg, $destFile, 85);
                            $relativePath = "uploads/{$folder}/{$filename}.jpg";
                        }

                        imagedestroy($srcImg);
                        imagedestroy($dstImg);

                        return $relativePath;
                    }
                }
            } catch (\Throwable $e) {
                // Fallback to standard move if GD encounters any issue
            }
        }

        // Fallback: standard file move
        $ext = $file->getClientOriginalExtension() ?: 'jpg';
        $destFileName = "{$filename}.{$ext}";
        $file->move($targetDir, $destFileName);

        return "uploads/{$folder}/{$destFileName}";
    }
}
