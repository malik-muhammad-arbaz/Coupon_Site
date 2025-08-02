<?php
include('includes/db.php');

$search_term = "";
$coupons = [];

if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
    $search_term = $conn->real_escape_string($_GET['q']);

    $sql = "SELECT c.*, s.name AS store_name, s.logo_url
            FROM coupons c
            JOIN stores s ON c.store_id = s.id
            WHERE c.title LIKE '%$search_term%' 
               OR c.description LIKE '%$search_term%'
               OR s.name LIKE '%$search_term%'
            ORDER BY c.created_at DESC";

    $result = $conn->query($sql);
    $coupons = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Results for "<?php echo htmlspecialchars($search_term); ?>"</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<h1>🔍 Search Results</h1>

<form method="GET" action="search.php">
    <input type="text" name="q" value="<?php echo htmlspecialchars($search_term); ?>" placeholder="Search coupons..." required>
    <button type="submit">Search</button>
</form>
<br>

<?php if ($search_term && count($coupons) > 0): ?>
    <div class="coupon-grid">
        <?php foreach ($coupons as $coupon): ?>
            <div class="coupon-card">
                <img src="<?php echo $coupon['logo_url']; ?>" alt="<?php echo $coupon['store_name']; ?>" width="80">
                <h2><?php echo $coupon['title']; ?></h2>
                <p><?php echo $coupon['description']; ?></p>
                <button onclick="copyCode('<?php echo $coupon['code']; ?>')">Copy Code: <?php echo $coupon['code']; ?></button><br>
                <a href="<?php echo $coupon['affiliate_link']; ?>" target="_blank">Visit Store</a>
            </div>
        <?php endforeach; ?>
    </div>
<?php elseif ($search_term): ?>
    <p>No coupons found for "<strong><?php echo htmlspecialchars($search_term); ?></strong>".</p>
<?php endif; ?>

<script>
    function copyCode(code) {
        navigator.clipboard.writeText(code);
        alert("Code copied: " + code);
    }
</script>

</body>
</html>
