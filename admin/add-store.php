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
    .form-wrapper {
      max-width: 600px;
      margin: 60px auto;
      background: #fff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    }

    .form-wrapper h2 {
      text-align: center;
      margin-bottom: 24px;
      color: #0077cc;
    }

    .form-wrapper label {
      display: block;
      font-weight: bold;
      margin: 12px 0 5px;
    }

    .form-wrapper input[type="text"],
    .form-wrapper input[type="url"],
    .form-wrapper input[type="file"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 15px;
    }

    .form-wrapper button {
      width: 100%;
      padding: 12px;
      background: #0077cc;
      color: #fff;
      font-weight: bold;
      border: none;
      border-radius: 6px;
      margin-top: 20px;
    }

    .form-wrapper button:hover {
      background: #005fa3;
    }

    .message {
      padding: 12px;
      margin-bottom: 15px;
      border-radius: 6px;
      font-weight: bold;
      text-align: center;
    }

    .message.success {
      background-color: #e8f5e9;
      color: #2e7d32;
    }

    .message.error {
      background-color: #ffebee;
      color: #c62828;
    }

    .form-wrapper a {
      display: inline-block;
      margin-top: 20px;
      color: #0077cc;
    }

    body.dark .form-wrapper {
      background: #2a2a2a;
      color: #eee;
    }

    body.dark input {
      background: #444;
      color: #fff;
      border-color: #555;
    }

    body.dark .form-wrapper button {
      background: #0077cc;
    }

    body.dark .form-wrapper button:hover {
      background: #005fa3;
    }
  </style>
</head>
<body>

  <div class="form-wrapper">
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

    <a href="dashboard.php">← Back to Dashboard</a>
  </div>

</body>
</html>
