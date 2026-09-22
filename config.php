<?php
/* ------------------------------------------------------------------
   ApexCV backend config
   Upload this file to the SAME folder as buildcv.html (htdocs)
------------------------------------------------------------------- */

// MySQL details (from InfinityFree > MySQL Databases)
const DB_HOST = 'sql208.infinityfree.com';
const DB_NAME = 'if0_42930818_apexx';
const DB_USER = 'if0_42930818';
const DB_PASS = '4628zAiFLJ2';

// Password to open view.php  -> CHANGE THIS
const VIEW_PASSWORD = 'ChangeMe123';

// Set to true only while testing: shows the real error message if something fails
const DEBUG = false;

date_default_timezone_set('Asia/Colombo');

function db(): PDO {
    static $pdo = null;
    if ($pdo) return $pdo;

    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
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
