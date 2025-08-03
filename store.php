<?php
include 'includes/db.php';

$slug = $_GET['slug'] ?? '';
$stmt = $conn->prepare("SELECT * FROM stores WHERE slug = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();
$store = $result->fetch_assoc();

if (!$store) {
    // Store not found, show a friendly message and exit
    echo "<h2>Store not found.</h2>";
    exit;
}

$store_id = $store['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="assets/style.css" />
  <title><?php echo htmlspecialchars($store['name']); ?> Coupons</title>
</head>
<body>
  <div class="coupon-list container">
    <div class="text-center">
      <img src="uploads/stores/<?php echo htmlspecialchars($store['logo_url']); ?>" alt="<?php echo htmlspecialchars($store['name']); ?>" style="height: 80px;" />
      <h2><?php echo htmlspecialchars($store['name']); ?> Coupons</h2>
    </div>

    <?php
    $stmt_coupons = $conn->prepare("SELECT * FROM coupons WHERE store_id = ? ORDER BY created_at DESC");
    $stmt_coupons->bind_param("i", $store_id);
    $stmt_coupons->execute();
    $coupons = $stmt_coupons->get_result();

    if ($coupons && $coupons->num_rows > 0):
        while ($c = $coupons->fetch_assoc()):
    ?>
      <div class="coupon-card flex">
        <div class="coupon-logo">
          <img src="<?php echo $c['image'] ? htmlspecialchars($c['image']) : 'uploads/coupons/default.png'; ?>" alt="Coupon Image" />
        </div>
        <div class="coupon-content">
          <h3><?php echo htmlspecialchars($c['title']); ?></h3>
          <p class="desc"><?php echo htmlspecialchars($c['description']); ?></p>
          <div class="coupon-meta">
            <span class="expiry">
              <?php
                $exp = strtotime($c['expiry_date']);
                echo $exp ? "Expires: " . date('M d, Y', $exp) : "No expiry";
              ?>
            </span>
          </div>
          <div class="coupon-actions">
            <button class="show-code" onclick="copyCode('<?php echo addslashes($c['code']); ?>')">Show Code</button>
            <a href="<?php echo htmlspecialchars($c['affiliate_link']); ?>" target="_blank" class="visit-store">Visit Store</a>
          </div>
        </div>
      </div>
    <?php
        endwhile;
    else:
    ?>
      <p>No coupons found for this store.</p>
    <?php endif; ?>
  </div>

  <script>
  function copyCode(code) {
    navigator.clipboard.writeText(code).then(() => {
      alert("Copied: " + code);
    }).catch(() => {
      alert("Failed to copy the code.");
    });
  }
  </script>

  <footer>
    <p>&copy; <?php echo date("Y"); ?> Coupon Site. All rights reserved.</p>
  </footer>
</body>
</html>

<?php
$stmt->close();
$stmt_coupons->close();
$conn->close();
?>
