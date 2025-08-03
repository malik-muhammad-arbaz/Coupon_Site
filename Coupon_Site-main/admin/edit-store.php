<?php
session_start();
include('../includes/db.php');
include('auth.php');

$message = "";

// Validate store ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: view-stores.php");
    exit();
}

$store_id = intval($_GET['id']);

// Fetch store details
$stmt = $conn->prepare("SELECT * FROM stores WHERE id = ?");
$stmt->bind_param("i", $store_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("⚠️ Store not found.");
}

$store = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $url = trim($_POST['website_url']);
    $logo_filename = $store['logo_url'];

    // Handle new logo upload
    if (!empty($_FILES['logo']['name'])) {
        $logoFile = $_FILES['logo'];
        $ext = strtolower(pathinfo($logoFile['name'], PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($ext, $allowed_exts)) {
            $logo_filename = uniqid("logo_") . "." . $ext;

            $upload_path = "../uploads/stores/";
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0755, true);
            }

            move_uploaded_file($logoFile['tmp_name'], $upload_path . $logo_filename);
        } else {
            $message = "⚠️ Invalid logo format.";
        }
    }

    if (empty($message)) {
        $update = $conn->prepare("UPDATE stores SET name=?, website_url=?, logo_url=? WHERE id=?");
        $update->bind_param("sssi", $name, $url, $logo_filename, $store_id);

        if ($update->execute()) {
            header("Location: view-stores.php?updated=1");
            exit();
        } else {
            $message = "❌ Update failed: " . $update->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Store</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="admin-dashboard">
        <h2>Edit Store</h2>

        <?php if ($message): ?>
            <p class="message-success"><strong><?php echo $message; ?></strong></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="admin-form">
            <label>Store Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($store['name']); ?>" required>

            <label>Website URL:</label>
            <input type="url" name="website_url" value="<?php echo htmlspecialchars($store['website_url']); ?>">

            <label>Current Logo:</label><br>
            <?php if ($store['logo_url']): ?>
                <img src="../uploads/stores/<?php echo $store['logo_url']; ?>" alt="Store Logo" width="120">
            <?php else: ?>
                <p>No logo uploaded.</p>
            <?php endif; ?>
            <br><br>

            <label>Upload New Logo (Optional):</label>
            <input type="file" name="logo" accept="image/*">

            <button type="submit">💾 Update Store</button>
        </form>

        <p><a href="view-stores.php">← Back to Store List</a></p>
    </div>
</body>
</html>
