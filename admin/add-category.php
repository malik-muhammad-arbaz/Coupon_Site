<?php
session_start();
include('../includes/db.php');
include('auth.php'); // Protect admin access

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);

    if (!empty($name)) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        if ($stmt->execute()) {
            $message = "✅ Category added successfully!";
        } else {
            $message = "❌ Error adding category: " . $stmt->error;
        }
    } else {
        $message = "⚠️ Category name is required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Category - Admin</title>
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
      max-width: 500px;
      background: #fdfdfd;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }

    label {
      font-weight: bold;
    }

    input[type="text"] {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
      margin-bottom: 20px;
    }

    button {
      width: 100%;
      padding: 12px;
      background: #0077cc;
      color: #fff;
      font-weight: bold;
      border-radius: 6px;
      border: none;
    }

    button:hover {
      background: #005fa3;
    }

    .message {
      padding: 10px;
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
  <h2>Add New Category</h2>

  <?php if ($message): ?>
    <div class="message <?php echo str_starts_with($message, '✅') ? 'success' : 'error'; ?>">
      <?php echo $message; ?>
    </div>
  <?php endif; ?>

  <form method="POST">
    <label for="name">Category Name:</label>
    <input type="text" name="name" id="name" placeholder="e.g. Electronics" required>

    <button type="submit">➕ Add Category</button>
  </form>
</div>

</body>
</html>
