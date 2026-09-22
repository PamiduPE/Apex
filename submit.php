<?php
require __DIR__ . '/config.php';
header('Content-Type: application/json; charset=utf-8');

function fail(string $msg, int $code = 400): void {
    http_response_code($code);
    echo json_encode(['ok' => false, 'error' => $msg]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') fail('Invalid request.', 405);

$get = function (string $key, int $max = 255): string {
    $v = trim((string)($_POST[$key] ?? ''));
    return function_exists('mb_substr') ? mb_substr($v, 0, $max) : substr($v, 0, $max);
};

$fullName    = $get('fullName', 150);
$dob         = $get('dob', 20);
$nationality = $get('nationality', 100);
$nic         = $get('nic', 50);
$passport    = $get('passport', 50);
$address     = $get('address', 1000);
$phone       = $get('phone', 50);
$email       = $get('email', 150);
$education   = $get('education', 5000);
$experience  = $get('experience', 8000);
$skills      = $get('skills', 5000);
$package     = $get('package', 50);

$packages = ['Local (Rs 750)', 'Global (Rs 950)', 'Premium (Rs 1450)'];

if ($fullName === '' || $dob === '' || $nationality === '' || $address === '' ||
    $phone === '' || $email === '' || $education === '' || $experience === '') {
    fail('Please fill in all required fields.');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) fail('Please enter a valid email address.');
if (!in_array($package, $packages, true))       fail('Please choose a package.');

try {
    // ---------- optional photo ----------
    $photoName = null;
    if (!empty($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        $p = $_FILES['photo'];
        if ($p['error'] !== UPLOAD_ERR_OK)  fail('Photo upload failed. Try a smaller image.');
        if ($p['size'] > 8 * 1024 * 1024)   fail('Photo is too large (max 8 MB).');

        $info = @getimagesize($p['tmp_name']);
        $types = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_WEBP => 'webp'];
        $ext = $types[$info[2] ?? 0] ?? null;
        if (!$ext) fail('Photo must be a JPG, PNG or WEBP image.');

        $dir = __DIR__ . '/uploads';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
            @file_put_contents($dir . '/index.html', ''); // blocks folder listing
        }
        $photoName = bin2hex(random_bytes(12)) . '.' . $ext;
        if (!move_uploaded_file($p['tmp_name'], $dir . '/' . $photoName)) {
            fail('Could not save the photo. Please try again.', 500);
        }
    }

    // ---------- save ----------
    $st = db()->prepare(
        'INSERT INTO submissions
         (full_name, dob, nationality, nic, passport, address, phone, email,
          education, experience, skills, package_name, photo, status, created_at)
         VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)'
    );
    $st->execute([
        $fullName, $dob, $nationality, $nic ?: null, $passport ?: null, $address, $phone, $email,
        $education, $experience, $skills ?: null, $package, $photoName, 'new', date('Y-m-d H:i:s'),
    ]);

    echo json_encode(['ok' => true, 'id' => (int)db()->lastInsertId()]);
} catch (Throwable $e) {
    error_log('ApexCV submit error: ' . $e->getMessage());
    fail(DEBUG ? $e->getMessage() : 'Server error. Please try again in a moment.', 500);
}
