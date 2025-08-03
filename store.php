<?php
include 'includes/db.php';

<<<<<<< HEAD
$slug = isset($_GET['slug']) ? $conn->real_escape_string($_GET['slug']) : '';
$store = $conn->query("SELECT * FROM stores WHERE slug = '$slug'")->fetch_assoc();

if (!$store) {
  echo "<h2 style='text-align:center;color:red;'>Store not found</h2>";
  exit();
}

$store_id = $store['id'];
$coupons = $conn->query("SELECT * FROM coupons WHERE store_id = $store_id ORDER BY created_at DESC");
=======
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
>>>>>>> 39796e476046c1c9642660c087361b22857755d1
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
<<<<<<< HEAD
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($store['name']); ?> - Coupons</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background: #f5f7fa;
      color: #333;
    }
    header, footer {
      background: #fff;
      padding: 15px 20px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    header h1, footer p {
      margin: 0;
      color: #0077cc;
    }
    nav ul {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin: 10px 0 0;
      padding: 0;
      list-style: none;
    }
    nav a {
      color: #333;
      text-decoration: none;
      font-weight: 600;
    }
    nav a:hover {
      color: #0077cc;
    }

    .store-header {
      background: linear-gradient(135deg, #0077cc, #00bcd4);
      color: white;
      text-align: center;
      padding: 50px 20px 30px;
      border-radius: 0 0 30px 30px;
    }
    .store-header img {
      max-height: 90px;
      background: #fff;
      padding: 12px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      margin-bottom: 15px;
    }
    .store-header h1 {
      font-size: 32px;
      margin-bottom: 10px;
    }
    .store-header p {
      font-size: 16px;
      color: #e0f7fa;
    }

    .coupon-list {
      max-width: 1000px;
      margin: 40px auto;
      padding: 0 20px;
    }

    .coupon-item {
      background: #fff;
      margin-bottom: 20px;
      padding: 20px;
      border-radius: 12px;
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      border: 1px solid #eaeaea;
      flex-wrap: wrap;
    }

    .coupon-info {
      flex: 1 1 70%;
    }

    .coupon-title {
      font-size: 18px;
      font-weight: bold;
      color: #0077cc;
      margin-bottom: 6px;
    }

    .coupon-desc {
      font-size: 14px;
      color: #555;
      margin-bottom: 10px;
    }

    .coupon-expiry {
      font-size: 13px;
      color: #888;
      font-style: italic;
    }

    .coupon-action {
      flex: 1 1 25%;
      display: flex;
      align-items: center;
      justify-content: flex-end;
    }

    .show-code-btn {
      background: #ff9800;
      color: #fff;
      font-weight: bold;
      border: none;
      padding: 12px 20px;
      border-radius: 8px;
      cursor: pointer;
      box-shadow: 0 4px 10px rgba(255,152,0,0.4);
      transition: 0.3s;
    }

    .show-code-btn:hover {
      background: #e68a00;
    }

    .modal-backdrop {
      display: none;
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0,0,0,0.6);
      z-index: 1000;
      align-items: center;
      justify-content: center;
    }

    .modal {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      max-width: 400px;
      width: 90%;
      text-align: center;
      position: relative;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .modal h2 {
      color: #0077cc;
      margin-bottom: 15px;
    }

    .modal-code {
      font-size: 24px;
      font-weight: bold;
      color: #333;
      margin-bottom: 20px;
    }

    .modal button, .modal a {
      display: inline-block;
      margin: 6px 4px;
      padding: 10px 16px;
      border-radius: 6px;
      font-weight: 600;
      text-decoration: none;
      transition: 0.3s ease;
    }

    .modal .copy-btn {
      background: #0077cc;
      color: white;
    }

    .modal .visit-store {
      background: #4caf50;
      color: white;
    }

    .modal .close-btn {
      position: absolute;
      top: 12px;
      right: 16px;
      font-size: 22px;
      color: #aaa;
      cursor: pointer;
    }

    @media (max-width: 600px) {
      nav ul {
        flex-direction: column;
      }
      .coupon-item {
        flex-direction: column;
        align-items: flex-start;
      }
      .coupon-action {
        width: 100%;
        margin-top: 12px;
        justify-content: flex-start;
      }
    }
  </style>
</head>
<body>

<header>
  <h1>RatedCoupons</h1>
  <nav>
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="all-stores.php">Stores</a></li>
      <li><a href="categories.php">Categories</a></li>
      <li><a href="about.php">About</a></li>
      <li><a href="contact.php">Contact</a></li>
    </ul>
  </nav>
</header>

<section class="store-header">
  <img src="uploads/stores/<?php echo htmlspecialchars($store['logo_url']); ?>" alt="<?php echo htmlspecialchars($store['name']); ?> Logo">
  <h1><?php echo htmlspecialchars($store['name']); ?></h1>
  <?php if (!empty($store['tagline'])): ?>
    <p><?php echo htmlspecialchars($store['tagline']); ?></p>
  <?php endif; ?>
</section>

<section class="coupon-list">
  <?php if ($coupons->num_rows > 0): ?>
    <?php while($coupon = $coupons->fetch_assoc()): ?>
      <div class="coupon-item">
        <div class="coupon-info">
          <div class="coupon-title"><?php echo htmlspecialchars($coupon['title']); ?></div>
          <div class="coupon-desc"><?php echo htmlspecialchars($coupon['description']); ?></div>
          <div class="coupon-expiry">
            <?php echo $coupon['expiry_date'] ? "Expires: " . date("M d, Y", strtotime($coupon['expiry_date'])) : "No expiry"; ?>
          </div>
        </div>
        <div class="coupon-action">
          <button class="show-code-btn"
            data-code="<?php echo htmlspecialchars($coupon['code']); ?>"
            data-link="<?php echo htmlspecialchars($coupon['affiliate_link']); ?>"
          >
            Show Code
          </button>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p style="text-align:center; color:#999; font-size:18px;">No coupons available for this store.</p>
  <?php endif; ?>
</section>

<!-- Modal Popup -->
<div class="modal-backdrop" id="couponModal">
  <div class="modal">
    <span class="close-btn" onclick="closeModal()">×</span>
    <h2>Your Coupon Code</h2>
    <div id="modalCode" class="modal-code">CODE123</div>
    <button class="copy-btn" onclick="copyCode()">Copy Code</button>
    <a id="modalLink" class="visit-store" href="#" target="_blank" rel="noopener">Visit Store</a>
=======
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
>>>>>>> 39796e476046c1c9642660c087361b22857755d1
  </div>
</div>

<<<<<<< HEAD
<footer>
  <p>&copy; <?php echo date("Y"); ?> RatedCoupons. All rights reserved.</p>
</footer>

<script>
  // Show modal
  document.querySelectorAll('.show-code-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.getElementById('modalCode').innerText = btn.dataset.code;
      document.getElementById('modalLink').href = btn.dataset.link;
      document.getElementById('couponModal').style.display = 'flex';
    });
  });

  function closeModal() {
    document.getElementById('couponModal').style.display = 'none';
=======
  <script>
  function copyCode(code) {
    navigator.clipboard.writeText(code).then(() => {
      alert("Copied: " + code);
    }).catch(() => {
      alert("Failed to copy the code.");
    });
>>>>>>> 39796e476046c1c9642660c087361b22857755d1
  }

  function copyCode() {
    const code = document.getElementById('modalCode').innerText;
    navigator.clipboard.writeText(code).then(() => {
      alert("Copied: " + code);
    });
  }
</script>

</body>
</html>
<<<<<<< HEAD
=======

<?php
$stmt->close();
$stmt_coupons->close();
$conn->close();
?>
>>>>>>> 39796e476046c1c9642660c087361b22857755d1
