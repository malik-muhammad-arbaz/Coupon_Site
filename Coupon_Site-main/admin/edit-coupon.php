<?php
session_start();
include('../includes/db.php');
include('auth.php');

// Validate coupon ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("⚠️ Invalid coupon ID.");
}

$id = intval($_GET['id']);
$message = "";

// Fetch existing coupon
$result = $conn->prepare("SELECT * FROM coupons WHERE id = ?");
$result->bind_param("i", $id);
$result->execute();
$couponData = $result->get_result();
$coupon = $couponData->fetch_assoc();

if (!$coupon) {
    die("⚠️ Coupon not found.");
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $code = trim($_POST['code']);
    $desc = trim($_POST['description']);
    $link = trim($_POST['affiliate_link']);
    $expiry = $_POST['expiry_date'];
    $store_id = intval($_POST['store_id']);

    // Update query
    $stmt = $conn->prepare("UPDATE coupons SET title=?, code=?, description=?, affiliate_link=?, expiry_date=?, store_id=? WHERE id=?");
    $stmt->bind_param("ssssssi", $title, $code, $desc, $link, $expiry, $store_id, $id);

    if ($stmt->execute()) {
        $message = "✅ Coupon updated successfully!";
        // Refresh data
        $refetch = $conn->prepare("SELECT * FROM coupons WHERE id = ?");
        $refetch->bind_param("i", $id);
        $refetch->execute();
        $coupon = $refetch->get_result()->fetch_assoc();
    } else {
        $message = "❌ Update failed: " . $stmt->error;
    }
}

// Fetch all stores for dropdown
$stores = $conn->query("SELECT id, name FROM stores");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Coupon</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="admin-dashboard">
        <h2>Edit Coupon</h2>

        <?php if ($message): ?>
            <p class="message-success"><strong><?php echo $message; ?></strong></p>
        <?php endif; ?>

        <form method="POST" class="admin-form">
            <label>Title:</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($coupon['title']); ?>" required>

            <label>Coupon Code:</label>
            <input type="text" name="code" value="<?php echo htmlspecialchars($coupon['code']); ?>">

            <label>Description:</label>
            <textarea name="description"><?php echo htmlspecialchars($coupon['description']); ?></textarea>

            <label>Affiliate Link:</label>
            <input type="url" name="affiliate_link" value="<?php echo htmlspecialchars($coupon['affiliate_link']); ?>">

            <label>Expiry Date:</label>
            <input type="date" name="expiry_date" value="<?php echo $coupon['expiry_date']; ?>">

            <label>Store:</label>
            <select name="store_id" required>
                <?php while ($s = $stores->fetch_assoc()): ?>
                    <option value="<?php echo $s['id']; ?>" <?php echo $s['id'] == $coupon['store_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($s['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <button type="submit">💾 Update Coupon</button>
        </form>

        <p><a href="view-coupons.php">← Back to Coupon List</a></p>
    </div>
</body>
</html>
