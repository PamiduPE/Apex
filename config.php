<?php
/* ------------------------------------------------------------------
   ApexCV backend config
   Upload this file to the SAME folder as buildcv.html (htdocs)
------------------------------------------------------------------- */

// MySQL details — Wasmer auto-injects DB_HOST / DB_PORT / DB_NAME / DB_USERNAME / DB_PASSWORD
// as environment variables when it detects your app needs a database. We use those first,
// and fall back to the values below (from the dashboard screenshot) if they aren't set.
const DB_HOST_FALLBACK = 'db.us-losa1.bengt.wasmernet.com';
const DB_PORT_FALLBACK = '16751';
const DB_NAME_FALLBACK = 'apex121';
const DB_USER_FALLBACK = 'user_5bd3fcb2';
const DB_PASS_FALLBACK = 'pw_srR20UDpuVf3tJ3Ap4wP0JdKbGzzex13';

function db_setting(string $env, string $fallback): string {
    $v = getenv($env);
    return ($v !== false && $v !== '') ? $v : $fallback;
}

// Password to open view.php  -> CHANGE THIS
const VIEW_PASSWORD = 'ChangeMe123';

// Set to true only while testing: shows the real error message if something fails
const DEBUG = false;

date_default_timezone_set('Asia/Colombo');

function db(): PDO {
    static $pdo = null;
    if ($pdo) return $pdo;

    $host = db_setting('DB_HOST', DB_HOST_FALLBACK);
    $port = db_setting('DB_PORT', DB_PORT_FALLBACK);
    $name = db_setting('DB_NAME', DB_NAME_FALLBACK);
    $user = db_setting('DB_USERNAME', DB_USER_FALLBACK);
    $pass = db_setting('DB_PASSWORD', DB_PASS_FALLBACK);

    $pdo = new PDO(
        'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $name . ';charset=utf8mb4',
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    // Table is created automatically the first time - nothing to import in phpMyAdmin
    $pdo->exec("CREATE TABLE IF NOT EXISTS submissions (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        full_name    VARCHAR(150) NOT NULL,
        dob          VARCHAR(20)  NOT NULL,
        nationality  VARCHAR(100) NOT NULL,
        nic          VARCHAR(50)  NULL,
        passport     VARCHAR(50)  NULL,
        address      TEXT NOT NULL,
        phone        VARCHAR(50)  NOT NULL,
        email        VARCHAR(150) NOT NULL,
        education    TEXT NOT NULL,
        experience   TEXT NOT NULL,
        skills       TEXT NULL,
        package_name VARCHAR(50)  NOT NULL,
        photo        VARCHAR(255) NULL,
        status       VARCHAR(10)  NOT NULL DEFAULT 'new',
        created_at   DATETIME NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    return $pdo;
}