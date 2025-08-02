<?php
include('includes/db.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="assets/style.css">
  <title>Store Coupons</title>
</head>
<body>
  <div class="coupon-list container">
    <?php
    if (isset($_GET['store_id']) && is_numeric($_GET['store_id'])) {
      $store_id = intval($_GET['store_id']);

      // Get store name and logo
      $store = $conn->query("SELECT * FROM stores WHERE id = $store_id")->fetch_assoc();

      if ($store):
    ?>
        <div class="text-center">
          <img src="uploads/stores/<?php echo $store['logo_url']; ?>" alt="<?php echo $store['name']; ?>" style="height: 80px;">
          <h2><?php echo htmlspecialchars($store['name']); ?> Coupons</h2>
        </div>

        <?php
        $coupons = $conn->query("SELECT * FROM coupons WHERE store_id = $store_id ORDER BY created_at DESC");

        if ($coupons && $coupons->num_rows > 0):
          while ($c = $coupons->fetch_assoc()):
        ?>
          <div class="coupon-card flex">
            <div class="coupon-logo">
              <img src="<?php echo $c['image'] ? $c['image'] : 'uploads/coupons/default.png'; ?>" alt="Coupon Image">
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
                <button class="show-code" onclick="copyCode('<?php echo $c['code']; ?>')">Show Code</button>
                <a href="<?php echo $c['affiliate_link']; ?>" target="_blank" class="visit-store">Visit Store</a>
              </div>
            </div>
          </div>
        <?php endwhile; else: ?>
          <p>No coupons found for this store.</p>
        <?php endif; ?>
      <?php else: ?>
        <p>Store not found.</p>
      <?php endif;
    } else {
      echo "<p>Invalid store ID.</p>";
    }
    ?>
  </div>

  <script>
  function copyCode(code) {
    navigator.clipboard.writeText(code);
    alert("Copied: " + code);
  }
  </script>

  <footer>
    <p>&copy; <?php echo date("Y"); ?> Coupon Site. All rights reserved.</p>
  </footer>
</body>
</html>

<?php if ($conn) $conn->close(); ?>
