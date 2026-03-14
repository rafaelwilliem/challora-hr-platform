<?php
/**
 * Jalankan seed dummy jobs
 * Akses: http://localhost/challorav2/run_seed.php
 * Atau: php run_seed.php
 */

require __DIR__ . '/config/database.php';

$sqlFile = __DIR__ . '/database/seed_dummy_jobs.sql';

if (!file_exists($sqlFile)) {
    die('File seed tidak ditemukan: ' . $sqlFile);
}

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_error) {
    die('Koneksi gagal: ' . $mysqli->connect_error);
}

$mysqli->set_charset('utf8mb4');

$sql = file_get_contents($sqlFile);
// Hilangkan baris USE database (karena kita sudah connect ke db)
$sql = preg_replace('/^\s*USE\s+\w+\s*;/im', '', $sql);

$ok = $mysqli->multi_query($sql);
if (!$ok) {
    die('Error: ' . $mysqli->error);
}

// Flush semua hasil multi_query
do {
    if ($result = $mysqli->store_result()) {
        $result->free();
    }
} while ($mysqli->next_result());

if ($mysqli->error) {
    die('Error: ' . $mysqli->error);
}

$mysqli->close();

$msg = 'Seed berhasil dijalankan. 50 lowongan dummy sudah terisi.';
$baseUrl = (isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . dirname($_SERVER['SCRIPT_NAME'] ?? '') . '/public/';
$baseUrl = rtrim($baseUrl, '/') . '/';

if (php_sapi_name() === 'cli') {
    echo $msg . "\n";
} else {
    echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>Seed</title></head><body>";
    echo "<p style='font-family:sans-serif;padding:20px;'>" . htmlspecialchars($msg) . "</p>";
    echo "<p><a href='" . htmlspecialchars($baseUrl) . "'>Ke beranda</a> | <a href='" . htmlspecialchars($baseUrl) . "index.php?url=jobs'>Lihat lowongan</a></p>";
    echo "</body></html>";
}
