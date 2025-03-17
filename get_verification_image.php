<?php
require 'db_connection.php';

if (isset($_GET['verification_id'])) {
    $verification_id = $_GET['verification_id'];

    $stmt = $conn->prepare("SELECT document FROM verification_requests WHERE verification_id = ?");
    $stmt->bind_param("s", $verification_id);
    $stmt->execute();
    $stmt->bind_result($image);
    $stmt->fetch();
    $stmt->close();
    $conn->close();

    if ($image) {
        header("Content-Type: image/jpeg");
        echo $image;
    } else {
        header("Content-Type: image/png");
        readfile("elements/default_image.png"); // Show default image if no document exists
    }
}
?>