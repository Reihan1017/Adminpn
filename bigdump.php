<?php
// BigDump: Staggered MySQL Dump Importer for PHP 7 & 8 (MySQLi)
// Downloaded & modified for compatibility
// Official: http://www.ozerov.de/bigdump/

$upload_dir = dirname(__FILE__);  // lokasi upload SQL
$db_server   = 'localhost';       // host database
$db_name     = 'jagowebdb';       // ganti sesuai nama database kamu
$db_username = 'root';            // username database
$db_password = '';                // password database (default XAMPP kosong)

// === Konfigurasi ===
$filename = '';                   // Kosongkan, nanti pilih lewat form
$linespersession = 3000;          // Jumlah query dieksekusi per batch
$delaypersession = 0;             // Delay antar batch (ms)
$comment = '#';                   // Karakter untuk komentar di SQL file
// ====================

// Cek MySQLi
if (!function_exists('mysqli_connect')) {
    die("PHP MySQLi extension tidak tersedia. Aktifkan di php.ini");
}

// Function untuk koneksi database
function connectDb($db_server, $db_username, $db_password, $db_name) {
    $connection = mysqli_connect($db_server, $db_username, $db_password, $db_name);
    if (!$connection) {
        die("Koneksi database gagal: " . mysqli_connect_error());
    }
    mysqli_set_charset($connection, "utf8");
    return $connection;
}

// ============================
// FORM UPLOAD
// ============================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['sqlfile'])) {
    $target_file = $upload_dir . '/' . basename($_FILES["sqlfile"]["name"]);
    if (move_uploaded_file($_FILES["sqlfile"]["tmp_name"], $target_file)) {
        echo "File berhasil diupload: " . basename($_FILES["sqlfile"]["name"]) . "<br>";
        $filename = $target_file;
    } else {
        die("Upload gagal.");
    }
}

echo "<h2>BigDump Importer (MySQLi Version)</h2>";

if (!$filename) {
    echo '<form method="post" enctype="multipart/form-data">
            Pilih file SQL: <input type="file" name="sqlfile" required>
            <input type="submit" value="Upload & Import">
          </form>';
    exit;
}

// ============================
// IMPORT PROSES
// ============================
$conn = connectDb($db_server, $db_username, $db_password, $db_name);

$handle = fopen($filename, "r");
if ($handle === false) {
    die("Tidak bisa buka file: $filename");
}

$query = '';
$lineCount = 0;

while (($line = fgets($handle)) !== false) {
    if (substr($line, 0, 2) == '--' || $line == '' || substr($line, 0, 1) == $comment) {
        continue;
    }
    $query .= $line;
    if (substr(trim($line), -1) == ';') {
        if (!mysqli_query($conn, $query)) {
            echo "Error query: " . mysqli_error($conn) . "<br>";
        }
        $query = '';
    }
    $lineCount++;
    if ($lineCount % $linespersession == 0) {
        flush();
        sleep($delaypersession);
    }
}

fclose($handle);
mysqli_close($conn);

echo "<p>✅ Import selesai!</p>";
