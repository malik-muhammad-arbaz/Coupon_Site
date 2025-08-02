<?php
session_start();
include('../includes/db.php');

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = sha1($_POST['password']); // Note: SHA1 is outdated, use bcrypt in production

    $sql = "SELECT * FROM admins WHERE username = '$username' AND password = '$password' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $_SESSION['admin'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <link rel="stylesheet" href="../assets/style.css">
  <style>
    body {
      background: #f0f2f5;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .login-box {
      background: #fff;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.08);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    .login-box h2 {
      margin-bottom: 24px;
      color: #0077cc;
    }

    .login-box input[type="text"],
    .login-box input[type="password"] {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
      margin-bottom: 16px;
      font-size: 16px;
    }

    .login-box button {
      width: 100%;
      padding: 12px;
      background: #0077cc;
      color: #fff;
      font-size: 16px;
      font-weight: bold;
      border-radius: 6px;
      border: none;
      transition: background 0.3s ease;
    }

    .login-box button:hover {
      background: #005fa3;
    }

    .error-message {
      color: red;
      margin-bottom: 12px;
    }

    body.dark .login-box {
      background: #2a2a2a;
      color: #ddd;
    }

    body.dark .login-box input {
      background: #444;
      color: #eee;
      border-color: #555;
    }

    body.dark .login-box button {
      background: #005fa3;
    }
  </style>
</head>
<body>

  <div class="login-box">
    <h2>Admin Login</h2>
    <?php if ($error): ?>
      <p class="error-message"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
  </div>

</body>
</html>
