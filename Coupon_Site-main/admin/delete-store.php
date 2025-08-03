<?php
session_start();
include('../includes/db.php');
include('auth.php'); // Protect this action for logged-in admins only

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // First check if store exists (optional but good practice)
    $check = $conn->prepare("SELECT id FROM stores WHERE id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $stmt = $conn->prepare("DELETE FROM stores WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            header("Location: view-stores.php?deleted=1");
            exit;
        } else {
            echo "<p style='color:red;'>❌ Error deleting store: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p style='color:red;'>⚠️ Store not found.</p>";
    }

    $check->close();
} else {
    echo "<p style='color:red;'>⚠️ Invalid store ID.</p>";
}
?>
