<?php
session_start();
include('../includes/db.php');
include('auth.php');

$message = "";

$storeResult = $conn->query("SELECT id, name FROM stores");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $code = trim($_POST['code']);
    $link = trim($_POST['affiliate_link']);
    $expiry = $_POST['expiry_date'];
    $store_id = intval($_POST['store_id']);
    $imageFile = $_FILES['image'];

    $image_name = "";

    if (!empty($imageFile['name'])) {
        $ext = strtolower(pathinfo($imageFile['name'], PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($ext, $allowed_exts)) {
            $image_name = uniqid("coupon_") . "." . $ext;
            move_uploaded_file($imageFile['tmp_name'], "../uploads/coupons/" . $image_name);
        } else {
            $message = "⚠️ Invalid image format.";
        }
    }

    if (empty($message) && !empty($title) && $store_id > 0) {
        $stmt = $conn->prepare("INSERT INTO coupons (title, description, code, affiliate_link, expiry_date, store_id, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssis", $title, $description, $code, $link, $expiry, $store_id, $image_name);
        if ($stmt->execute()) {
            $message = "✅ Coupon added successfully!";
        } else {
            $message = "❌ Error adding coupon: " . $stmt->error;
        }
    } elseif (empty($message)) {
        $message = "⚠️ Title and Store selection are required.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Coupon</title>
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

        input, select, textarea {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
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
    <h2>Add New Coupon</h2>

    <?php if ($message): ?>
        <div class="message <?php echo str_starts_with($message, '✅') ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Coupon Title:</label>
        <input type="text" name="title" required>

        <label>Description:</label>
        <textarea name="description" rows="4"></textarea>

        <label>Coupon Code:</label>
        <input type="text" name="code">

        <label>Affiliate Link:</label>
        <input type="url" name="affiliate_link">

        <label>Expiry Date:</label>
        <input type="date" name="expiry_date">

        <label>Store:</label>
        <select name="store_id" required>
            <option value="">-- Select Store --</option>
            <?php while ($store = $storeResult->fetch_assoc()): ?>
                <option value="<?php echo $store['id']; ?>"><?php echo htmlspecialchars($store['name']); ?></option>
            <?php endwhile; ?>
        </select>

        <label>Coupon Image:</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit">➕ Add Coupon</button>
    </form>
</div>
</body>
</html>