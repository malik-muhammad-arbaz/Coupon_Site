<?php
session_start();
include('../includes/db.php');
include('auth.php'); // Protect admin access

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $url = trim($_POST['website_url']);
    $logoFile = $_FILES['logo'];

    $logo_filename = "";

    if (!empty($logoFile['name'])) {
        $ext = strtolower(pathinfo($logoFile['name'], PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($ext, $allowed_exts)) {
            $logo_filename = uniqid("logo_") . "." . $ext;

            $upload_dir = "../uploads/stores/";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            move_uploaded_file($logoFile['tmp_name'], $upload_dir . $logo_filename);
        } else {
            $message = "⚠️ Invalid file format. Only JPG, PNG, GIF, or WEBP allowed.";
        }
    }

    if (empty($message) && !empty($name)) {
        $stmt = $conn->prepare("INSERT INTO stores (name, logo_url, website_url) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $logo_filename, $url);
        if ($stmt->execute()) {
            $message = "✅ Store added successfully!";
        } else {
            $message = "❌ Error adding store: " . $stmt->error;
        }
    } elseif (empty($message)) {
        $message = "⚠️ Store name is required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Store - Admin Panel</title>
  <link rel="stylesheet" href="../assets/style.css">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(120deg, #f4f6f9, #dbe9f4);
      display: flex;
      height: 100vh;
      overflow: hidden;
    }

    .sidebar {
      width: 240px;
      background: #0077cc;
      padding: 30px 20px;
      display: flex;
      flex-direction: column;
      gap: 15px;
      color: #fff;
      box-shadow: 2px 0 12px rgba(0, 0, 0, 0.1);
    }

    .sidebar h2 {
      font-size: 24px;
      margin-bottom: 30px;
      text-align: center;
      color: #fff;
    }

    .sidebar a {
      color: #fff;
      text-decoration: none;
      padding: 12px 15px;
      border-radius: 6px;
      background: rgba(255, 255, 255, 0.1);
      transition: all 0.3s ease-in-out;
    }

    .sidebar a:hover {
      background: #fff;
      color: #0077cc;
      transform: scale(1.05);
    }

    .main-content {
      flex: 1;
      padding: 40px;
      background: #fff;
      overflow-y: auto;
      animation: fadeIn 0.5s ease-in-out;
    }

    .main-content h2 {
      font-size: 28px;
      color: #0077cc;
      margin-bottom: 20px;
    }

    form {
      max-width: 600px;
      background: #fdfdfd;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }

    label {
      font-weight: bold;
    }

    input[type="text"],
    input[type="url"],
    input[type="file"] {
      width: 100%;
      padding: 12px;
      margin-top: 8px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    button {
      padding: 14px;
      background: #0077cc;
      color: #fff;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      font-size: 16px;
      cursor: pointer;
      width: 100%;
    }

    button:hover {
      background-color: #005fa3;
    }

    .message {
      padding: 12px;
      margin-bottom: 20px;
      border-radius: 6px;
      font-weight: bold;
    }

    .message.success {
      background-color: #e8f5e9;
      color: #2e7d32;
    }

    .message.error {
      background-color: #ffebee;
      color: #c62828;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>Admin Menu</h2>
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="add-coupon.php">➕ Add Coupon</a>
    <a href="add-store.php">🏬 Add Store</a>
    <a href="add-category.php">📁 Add Category</a>
    <a href="view-coupons.php">🎟️ View Coupons</a>
    <a href="view-stores.php">🏪 View Stores</a>
    <a href="view-categories.php">🗂️ View Categories</a>
    <a href="view-users.php">👥 View Users</a>
    <a href="logout.php">🚪 Logout</a>
  </div>

  <div class="main-content">
    <h2>Add New Store</h2>

    <?php if ($message): ?>
      <div class="message <?php echo str_starts_with($message, '✅') ? 'success' : 'error'; ?>">
        <?php echo $message; ?>
      </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <label for="name">Store Name:</label>
      <input type="text" id="name" name="name" required>

      <label for="website_url">Website URL:</label>
      <input type="url" id="website_url" name="website_url">

      <label for="logo">Upload Store Logo:</label>
      <input type="file" id="logo" name="logo" accept="image/*">

      <button type="submit">➕ Add Store</button>
    </form>
  </div>
</body>
</html>
