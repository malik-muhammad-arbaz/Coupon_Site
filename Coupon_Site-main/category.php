<?php
include('includes/db.php');

$cat_id = isset($_GET['cat_id']) ? intval($_GET['cat_id']) : 0;

if ($cat_id == 0) {
    die("❌ Invalid category selected.");
}

// Get category name
$cat_result = $conn->query("SELECT name FROM categories WHERE id = $cat_id");
if ($cat_result->num_rows == 0) {
    die("❌ Category not found.");
}
$cat = $cat_result->fetch_assoc();

// Get coupons for that category
$coupon_result = $conn->query("SELECT c.*, s.name AS store_name, s.logo_url FROM coupons c 
                                JOIN stores s ON c.store_id = s.id 
                                WHERE c.category_id = $cat_id
                                ORDER BY c.created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Category: <?php echo htmlspecialchars($cat['name']); ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<h2>📂 Category: <?php echo htmlspecialchars($cat['name']); ?></h2>
<a href="index.php">← Back to Home</a>
<hr>

<div class="coupon-grid">
<?php if ($coupon_result->num_rows > 0): ?>
    <?php while ($coupon = $coupon_result->fetch_assoc()): ?>
        <div class="coupon-card">
            <img src="<?php echo $coupon['logo_url']; ?>" width="80">
            <h3><?php echo $coupon['title']; ?></h3>
            <p><?php echo $coupon['description']; ?></p>
            <button onclick="copyCode('<?php echo $coupon['code']; ?>')">
                Copy Code: <?php echo $coupon['code']; ?>
            </button>
            <a href="<?php echo $coupon['affiliate_link']; ?>" target="_blank">Visit Store</a>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No coupons found in this category.</p>
<?php endif; ?>
</div>

<script>
function copyCode(code) {
    navigator.clipboard.writeText(code);
    alert("Copied: " + code);
}
</script>

</body>
</html>
