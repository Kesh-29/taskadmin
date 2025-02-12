<?php
$host = "127.0.0.1:3307"; // Use the correct port
$user = "root";
$pass = "ogatarizu";  // Change if necessary
$dbname = "taskconnect";

// Enable error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $user, $pass, $dbname);
    $conn->set_charset("utf8mb4"); // Set correct charset
} catch (mysqli_sql_exception $e) {
    die("❌ Connection failed: " . $e->getMessage());
}
?>