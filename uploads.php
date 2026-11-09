<?php
declare(strict_types=1);

/**
 * Safe image upload: verifies the file really is an image, re-encodes it with GD
 * (stripping metadata and any embedded payload), resizes, and stores it under a
 * random filename in public/uploads/.
 *
 * Returns [path|null, error|null]. Path is site-relative, e.g. "uploads/ab12.jpg".
 */
function save_image_upload(string $field, int $maxWidth = 1600): array
{
    if (empty($_FILES[$field]) || !is_array($_FILES[$field]) || ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return [null, null];
    }
    $f = $_FILES[$field];
    if ($f['error'] !== UPLOAD_ERR_OK) {
        return [null, $f['error'] === UPLOAD_ERR_INI_SIZE || $f['error'] === UPLOAD_ERR_FORM_SIZE
            ? 'The image is too large. Please choose a file under 5 MB.' : 'The image could not be uploaded. Please try again.'];
    }
    if ($f['size'] > 5 * 1024 * 1024) {
        return [null, 'The image is too large. Please choose a file under 5 MB.'];
    }
    if (!is_uploaded_file($f['tmp_name'])) {
        return [null, 'Invalid upload.'];
    }
    $info = @getimagesize($f['tmp_name']);
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($f['tmp_name']);
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (!$info || !isset($allowed[$mime])) {
        return [null, 'Only JPG, PNG or WebP images are allowed.'];
    }
    if ($info[0] > 8000 || $info[1] > 8000) {
        return [null, 'The image dimensions are too large.'];
    }
    $src = match ($mime) {
        'image/jpeg' => @imagecreatefromjpeg($f['tmp_name']),
        'image/png' => @imagecreatefrompng($f['tmp_name']),
        'image/webp' => @imagecreatefromwebp($f['tmp_name']),
    };
    if (!$src) {
        return [null, 'The image could not be processed.'];
    }
    // Respect EXIF orientation for JPEGs.
    if ($mime === 'image/jpeg' && function_exists('exif_read_data')) {
        $exif = @exif_read_data($f['tmp_name']);
        $o = (int)($exif['Orientation'] ?? 1);
        $angle = [3 => 180, 6 => -90, 8 => 90][$o] ?? 0;
        if ($angle) {
            $rot = imagerotate($src, $angle, 0);
            if ($rot) {
                $src = $rot;
            }
        }
    }
    $w = imagesx($src);
    $h = imagesy($src);
    if ($w > $maxWidth) {
        $nh = (int)round($h * ($maxWidth / $w));
        $dst = imagecreatetruecolor($maxWidth, $nh);
        if ($mime !== 'image/jpeg') {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
        }
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $maxWidth, $nh, $w, $h);
        $src = $dst;
    }
    $dir = PUBLIC_DIR . '/uploads';
    if (!is_dir($dir) && !@mkdir($dir, 0755, true)) {
        return [null, 'The uploads folder is not writable on the server.'];
    }
    $ext = $mime === 'image/png' ? 'png' : ($mime === 'image/webp' ? 'webp' : 'jpg');
    $name = bin2hex(random_bytes(10)) . '.' . $ext;
    $dest = $dir . '/' . $name;
    $ok = match ($ext) {
        'png' => imagepng($src, $dest, 7),
        'webp' => imagewebp($src, $dest, 84),
        default => imagejpeg($src, $dest, 85),
    };
    if (!$ok) {
        return [null, 'The uploads folder is not writable on the server.'];
    }
    @chmod($dest, 0644);
    return ['uploads/' . $name, null];
}

function delete_upload(?string $path): void
{
    if (!$path || !str_starts_with($path, 'uploads/') || str_contains($path, '..')) {
        return;
    }
    $file = PUBLIC_DIR . '/' . $path;
    if (is_file($file)) {
        @unlink($file);
    }
}
