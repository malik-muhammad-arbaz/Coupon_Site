<?php
include('includes/db.php');

$cat_id = isset($_GET['cat_id']) ? intval($_GET['cat_id']) : 0;

if ($cat_id == 0) {
    die("❌ Invalid category selected.");
}

$cat_result = $conn->query("SELECT name FROM categories WHERE id = $cat_id");
if ($cat_result->num_rows == 0) {
    die("❌ Category not found.");
}
$cat = $cat_result->fetch_assoc();

$coupon_result = $conn->query("SELECT c.*, s.name AS store_name, s.logo_url FROM coupons c 
                                JOIN stores s ON c.store_id = s.id 
                                WHERE c.category_id = $cat_id
                                ORDER BY c.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Category: <?php echo htmlspecialchars($cat['name']); ?></title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        h2 {
            color: #0077cc;
            text-align: center;
        }

        a {
            color: #0077cc;
            text-decoration: none;
            font-weight: 500;
        }

        a:hover {
            text-decoration: underline;
        }

        .coupon-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
            margin-top: 30px;
        }

        .coupon-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 8px 18px rgba(0,0,0,0.05);
            text-align: center;
            transition: transform 0.2s ease;
        }

        .coupon-card:hover {
            transform: translateY(-4px);
        }

        .coupon-card img {
            height: 60px;
            margin-bottom: 10px;
        }

        .coupon-card h3 {
            margin: 10px 0 5px;
            font-size: 18px;
            color: #333;
        }

        .coupon-card p {
            font-size: 14px;
            color: #555;
            min-height: 60px;
        }

        .coupon-card button {
            background-color: #0077cc;
            color: #fff;
            border: none;
            padding: 10px 16px;
            margin: 10px 0;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        .coupon-card button:hover {
            background-color: #005fa3;
        }

        .coupon-card a {
            display: inline-block;
            margin-top: 10px;
            font-weight: bold;
            color: #2e7d32;
        }

        .coupon-card a:hover {
            color: #1b5e20;
        }

        .back-link {
            display: inline-block;
            margin: 20px 0;
            font-size: 16px;
        }

        @media (max-width: 600px) {
            .coupon-card p {
                font-size: 13px;
            }
        }
    </style>
</head>
<body>

<h2>📂 Category: <?php echo htmlspecialchars($cat['name']); ?></h2>
<a class="back-link" href="index.php">← Back to Home</a>
<hr>

<div class="coupon-grid">
<?php if ($coupon_result->num_rows > 0): ?>
    <?php while ($coupon = $coupon_result->fetch_assoc()): ?>
        <div class="coupon-card">
            <img src="<?php echo htmlspecialchars($coupon['logo_url']); ?>" alt="Store Logo">
            <h3><?php echo htmlspecialchars($coupon['title']); ?></h3>
            <p><?php echo htmlspecialchars($coupon['description']); ?></p>
            <?php if ($coupon['code']): ?>
                <button onclick="copyCode('<?php echo htmlspecialchars($coupon['code']); ?>')">
                    Copy Code: <?php echo htmlspecialchars($coupon['code']); ?>
                </button>
            <?php else: ?>
                <button disabled>No Code Required</button>
            <?php endif; ?>
            <br>
            <a href="<?php echo htmlspecialchars($coupon['affiliate_link']); ?>" target="_blank">Visit Store →</a>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p style="text-align:center;">No coupons found in this category.</p>
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
