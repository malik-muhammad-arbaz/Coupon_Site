<?php
session_start();
include('../includes/db.php');
include('auth.php');

$message = "";

// Fetch all stores for dropdown
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
    <style>
        /* Reset and base */
        body {
          font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
          background: #f5f7fa;
          margin: 0;
          padding: 0;
          color: #333;
        }

        .admin-dashboard {
          max-width: 600px;
          background: #fff;
          margin: 60px auto;
          padding: 30px 40px;
          border-radius: 12px;
          box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .admin-dashboard h2 {
          color: #0077cc;
          text-align: center;
          margin-bottom: 30px;
          font-weight: 700;
        }

        .message-success {
          background-color: #e8f5e9;
          color: #2e7d32;
          padding: 15px;
          border-radius: 8px;
          margin-bottom: 20px;
          font-weight: 600;
          text-align: center;
          box-shadow: 0 2px 6px rgba(46,125,50,0.2);
        }

        .admin-form label {
          display: block;
          font-weight: 600;
          margin-bottom: 6px;
          margin-top: 18px;
          color: #444;
        }

        .admin-form input[type="text"],
        .admin-form input[type="url"],
        .admin-form input[type="date"],
        .admin-form select,
        .admin-form textarea,
        .admin-form input[type="file"] {
          width: 100%;
          padding: 12px 14px;
          font-size: 15px;
          border: 1.8px solid #ccc;
          border-radius: 8px;
          transition: border-color 0.3s ease;
          box-sizing: border-box;
        }

        .admin-form input[type="text"]:focus,
        .admin-form input[type="url"]:focus,
        .admin-form input[type="date"]:focus,
        .admin-form select:focus,
        .admin-form textarea:focus,
        .admin-form input[type="file"]:focus {
          border-color: #0077cc;
          outline: none;
          box-shadow: 0 0 8px rgba(0,119,204,0.3);
        }

        .admin-form textarea {
          resize: vertical;
          min-height: 80px;
        }

        .admin-form button {
          margin-top: 30px;
          background-color: #0077cc;
          color: #fff;
          font-size: 18px;
          font-weight: 700;
          padding: 14px;
          border: none;
          border-radius: 10px;
          cursor: pointer;
          width: 100%;
          transition: background-color 0.3s ease;
        }

        .admin-form button:hover {
          background-color: #005fa3;
        }

        .admin-dashboard p a {
          display: inline-block;
          margin-top: 28px;
          color: #0077cc;
          text-decoration: none;
          font-weight: 600;
          transition: color 0.2s ease;
        }

        .admin-dashboard p a:hover {
          color: #005fa3;
        }
    </style>
</head>
<body>
    <div class="admin-dashboard">
        <h2>Add New Coupon</h2>

        <?php if ($message): ?>
            <p class="message-success"><strong><?php echo htmlspecialchars($message); ?></strong></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="admin-form">
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

        <p><a href="dashboard.php">← Back to Dashboard</a></p>
    </div>
</body>
</html>
