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
    .form-wrapper {
      max-width: 500px;
      margin: 80px auto;
      background: #fff;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }

    .form-wrapper h2 {
      text-align: center;
      color: #0077cc;
      margin-bottom: 24px;
    }

    .form-wrapper label {
      font-weight: bold;
    }

    .form-wrapper input[type="text"] {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
      margin-bottom: 20px;
    }

    .form-wrapper button {
      width: 100%;
      padding: 12px;
      background: #0077cc;
      color: #fff;
      font-weight: bold;
      border-radius: 6px;
      border: none;
    }

    .form-wrapper button:hover {
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

    .form-wrapper a {
      display: inline-block;
      margin-top: 15px;
      color: #0077cc;
    }

    body.dark .form-wrapper {
      background: #2c2c2c;
      color: #eee;
    }

    body.dark .form-wrapper input,
    body.dark .form-wrapper button {
      background: #444;
      color: #fff;
      border-color: #555;
    }

    body.dark .form-wrapper button:hover {
      background: #005fa3;
    }
  </style>
</head>
<body>

  <div class="form-wrapper">
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

    <a href="dashboard.php">← Back to Dashboard</a>
  </div>

</body>
</html>
