<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["admin_id"]) && isset($_POST["action"])) {
    $admin_id = intval($_POST["admin_id"]);
    $action = $_POST["action"];

    if ($action === "delete") {
        $query = "UPDATE admins SET is_deleted = 1 WHERE admin_id = ?";
    } else {
        echo "Invalid action.";
        exit();
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $admin_id);

    if ($stmt->execute()) {
        echo "Admin successfully deleted!";
    } else {
        echo "Error updating admin.";
    }

    $stmt->close();
    $conn->close();
}
?>