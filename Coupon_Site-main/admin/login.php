<?php
session_start();
include('../includes/db.php');

// Ensure admins table exists with required fields
$conn->query("CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_approved TINYINT(1) DEFAULT 0
)");

$error = "";
$register_success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['register'])) {
        $new_username = trim($_POST['new_username']);
        $new_email = trim($_POST['new_email']);
        $new_password_raw = trim($_POST['new_password']);
        $confirm_password = trim($_POST['confirm_password']);
        $captcha_answer = trim($_POST['captcha_answer']);

        $expected_answer = $_SESSION['captcha'] ?? '';

        if ($new_username === '' || $new_email === '' || $new_password_raw === '' || $confirm_password === '' || $captcha_answer === '') {
            $error = "Please fill in all fields.";
        } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email address.";
        } elseif ($new_password_raw !== $confirm_password) {
            $error = "Passwords do not match.";
        } elseif ($captcha_answer != $expected_answer) {
            $error = "Invalid CAPTCHA answer.";
        } else {
            $new_password = password_hash($new_password_raw, PASSWORD_BCRYPT);

            $check_stmt = $conn->prepare("SELECT id FROM admins WHERE username = ? OR email = ?");
            if ($check_stmt) {
                $check_stmt->bind_param("ss", $new_username, $new_email);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();

                if ($check_result && $check_result->num_rows > 0) {
                    $error = "Username or Email already exists.";
                } else {
                    $insert_stmt = $conn->prepare("INSERT INTO admins (username, email, password, is_approved) VALUES (?, ?, ?, 0)");
                    if ($insert_stmt) {
                        $insert_stmt->bind_param("sss", $new_username, $new_email, $new_password);
                        $insert_stmt->execute();
                        $register_success = "✅ Registered successfully. Awaiting admin approval.";
                    } else {
                        $error = "Registration failed: " . $conn->error;
                    }
                }
            } else {
                $error = "Registration check failed: " . $conn->error;
            }
        }
    } else {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        if ($username === '' || $password === '') {
            $error = "Please enter both username and password.";
        } else {
            $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ? AND is_approved = 1 LIMIT 1");
            if ($stmt) {
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result && $result->num_rows === 1) {
                    $admin = $result->fetch_assoc();
                    if (password_verify($password, $admin['password'])) {
                        $_SESSION['admin'] = $admin['username'];
                        header("Location: dashboard.php");
                        exit();
                    } else {
                        $error = "Incorrect password.";
                    }
                } else {
                    $error = "Account not found or not approved.";
                }
            } else {
                $error = "Login query failed: " . $conn->error;
            }
        }
    }
}

// Simple CAPTCHA generation
$num1 = rand(1, 10);
$num2 = rand(1, 10);
$_SESSION['captcha'] = $num1 + $num2;
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
    .login-box input[type="password"],
    .login-box input[type="email"] {
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

    .success-message {
      color: green;
      margin-bottom: 12px;
    }

    .toggle-link {
      color: #0077cc;
      font-size: 14px;
      cursor: pointer;
      margin-top: 10px;
      display: block;
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
    <h2 id="form-title">Admin Login</h2>
    <?php if ($error): ?>
      <p class="error-message"><?php echo $error; ?></p>
    <?php endif; ?>
    <?php if ($register_success): ?>
      <p class="success-message"><?php echo $register_success; ?></p>
    <?php endif; ?>

    <form method="POST" id="login-form">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
      <span class="toggle-link" onclick="showRegister()">Don't have an account? Register</span>
    </form>

    <form method="POST" id="register-form" style="display:none">
      <input type="text" name="new_username" placeholder="New Username" required>
      <input type="email" name="new_email" placeholder="Email" required>
      <input type="password" name="new_password" placeholder="New Password" required>
      <input type="password" name="confirm_password" placeholder="Confirm Password" required>
      <input type="text" name="captcha_answer" placeholder="What is <?php echo $num1; ?> + <?php echo $num2; ?>?" required>
      <button type="submit" name="register">Register</button>
      <span class="toggle-link" onclick="showLogin()">Already have an account? Login</span>
    </form>
  </div>

  <script>
    function showRegister() {
      document.getElementById('login-form').style.display = 'none';
      document.getElementById('register-form').style.display = 'block';
      document.getElementById('form-title').innerText = 'Register Admin';
    }

    function showLogin() {
      document.getElementById('login-form').style.display = 'block';
      document.getElementById('register-form').style.display = 'none';
      document.getElementById('form-title').innerText = 'Admin Login';
    }
  </script>
</body>
</html>