<?php include 'includes/db.php'; ?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RatedCoupons - Home</title>
  <style>
    /* =============================
       GLOBAL BASE STYLES
    ============================= */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    html, body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f6f9;
      color: #333;
      line-height: 1.6;
      scroll-behavior: smooth;
      min-height: 100vh;
    }
    a {
      color: #0077cc;
      text-decoration: none;
      transition: 0.3s;
    }
    a:hover {
      color: #005fa3;
      text-decoration: underline;
    }
    ul {
      list-style: none;
    }
    img {
      display: block;
      max-width: 100%;
      height: auto;
      object-fit: contain;
    }
    button {
      cursor: pointer;
      border: none;
      background: #0077cc;
      color: #fff;
      padding: 10px 18px;
      border-radius: 6px;
      font-weight: 600;
      transition: background 0.3s ease;
    }
    button:hover {
      background: #005fa3;
    }

    /* =============================
       HEADER & NAVIGATION
    ============================= */
    .site-header {
      background: #fff;
      border-bottom: 1px solid #eaeaea;
      padding: 15px 0;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
      position: sticky;
      top: 0;
      z-index: 1000;
    }
    .container {
      max-width: 1200px;
      margin: auto;
      padding: 0 20px;
    }
    .flex {
      display: flex;
    }
    .flex-center {
      align-items: center;
    }
    .space-between {
      justify-content: space-between;
    }
    .logo a {
      font-size: 28px;
      font-weight: bold;
      color: #0077cc;
      text-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }
    .main-nav ul {
      display: flex;
      gap: 24px;
    }
    .main-nav li a {
      font-size: 16px;
      color: #333;
      font-weight: 500;
      padding: 6px 10px;
      transition: 0.3s;
    }
    .main-nav li a:hover {
      background: #f0f0f0;
      border-radius: 4px;
    }

    /* =============================
       HERO SECTION
    ============================= */
    .hero {
      background: linear-gradient(135deg, #0077cc, #00bcd4);
      color: #fff;
      padding: 70px 20px;
      text-align: center;
    }
    .animated-heading {
      font-size: 36px;
      margin-bottom: 12px;
      animation: fadeInUp 1s ease;
    }
    @keyframes fadeInUp {
      0% {opacity: 0; transform: translateY(20px);}
      100% {opacity: 1; transform: translateY(0);}
    }

    /* =============================
       CATEGORIES, STORES, COUPONS
    ============================= */
    .text-center {
      text-align: center;
    }
    .category-list {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
      justify-content: center;
      margin-top: 20px;
    }
    .category-btn {
      background: #fff;
      border: 2px solid #0077cc;
      color: #0077cc;
      font-weight: 600;
      border-radius: 6px;
      padding: 10px 16px;
      transition: 0.3s ease;
    }
    .category-btn:hover {
      background: #0077cc;
      color: #fff;
    }

    .coupon-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 24px;
      margin-top: 30px;
    }

    .coupon-card {
      background: #fff;
      border-radius: 12px;
      padding: 18px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      transition: transform 0.3s ease;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      border: 1px solid #ddd;
    }

    .coupon-card:hover {
      transform: translateY(-6px);
    }

    .coupon-logo {
      text-align: center;
      margin-bottom: 12px;
    }

    .coupon-content h3 {
      font-size: 18px;
      margin-bottom: 8px;
    }

    .coupon-content .desc {
      font-size: 14px;
      color: #666;
      margin-bottom: 10px;
      min-height: 40px;
    }

    .coupon-meta {
      font-size: 13px;
      color: #888;
      margin-bottom: 12px;
    }

    .coupon-actions {
      display: flex;
      justify-content: space-between;
      gap: 10px;
      flex-wrap: wrap;
    }

    .show-code {
      background-color: #ff9800;
    }

    .show-code:hover {
      background-color: #e68900;
    }

    .visit-store {
      background-color: #4caf50;
      color: #fff;
      padding: 10px 14px;
      border-radius: 6px;
      text-decoration: none;
    }

    .visit-store:hover {
      background-color: #3e8e41;
    }

    /* =============================
       FOOTER
    ============================= */
    .site-footer {
      background: #f0f0f0;
      padding: 30px 0;
      font-size: 14px;
      text-align: center;
      color: #666;
      border-top: 1px solid #ddd;
      margin-top: 50px;
    }

    /* =============================
       RESPONSIVE
    ============================= */
    @media (max-width: 768px) {
      .main-nav ul {
        flex-direction: column;
        gap: 12px;
        align-items: center;
      }
      .coupon-actions {
        flex-direction: column;
        align-items: stretch;
      }
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header class="site-header">
    <div class="container flex flex-center space-between">
      <h1 class="logo"><a href="index.php">RatedCoupons</a></h1>
      <nav class="main-nav">
        <ul class="flex">
          <li><a href="index.php">Home</a></li>
          <li><a href="all-stores.php">Stores</a></li>
          <li><a href="categories.php">Categories</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="contact.php">Contact</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <h1 class="animated-heading">Save Big with Latest Coupons & Deals</h1>
    <p>Discover verified offers from top brands. Updated daily to bring you fresh savings!</p>
  </section>

  <!-- Categories Section -->
  <section class="container">
    <h2 class="text-center">Categories</h2>
    <div class="category-list flex wrap">
      <?php
      $cats = $conn->query("SELECT * FROM categories ORDER BY name ASC");
      while ($cat = $cats->fetch_assoc()):
      ?>
        <a href="category.php?cat_id=<?php echo $cat['id']; ?>">
          <button class="category-btn"><?php echo $cat['name']; ?></button>
        </a>
      <?php endwhile; ?>
    </div>
  </section>

  <!-- Featured Stores -->
  <section class="container">
    <h2 class="text-center">Featured Stores</h2>
    <div class="coupon-grid">
      <?php
      $stores = $conn->query("SELECT * FROM stores ORDER BY id DESC LIMIT 6");
      while($s = $stores->fetch_assoc()):
      ?>
        <div class="coupon-card">
          <div class="coupon-logo text-center">
            <img src="uploads/stores/<?php echo $s['logo_url']; ?>" alt="<?php echo $s['name']; ?> Logo" style="max-height: 80px;">
          </div>
          <div class="coupon-content text-center">
            <h3><a href="store.php?store_id=<?php echo $s['id']; ?>"><?php echo $s['name']; ?></a></h3>
            <a href="store.php?store_id=<?php echo $s['id']; ?>" class="visit-store">View Coupons</a>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </section>

  <!-- Latest Coupons -->
  <section class="container">
    <h2 class="text-center">Latest Coupons</h2>
    <div class="coupon-grid">
      <?php
      $coupons = $conn->query("SELECT c.*, s.name AS store_name, s.logo_url FROM coupons c JOIN stores s ON c.store_id = s.id ORDER BY c.created_at DESC LIMIT 12");
      while ($coupon = $coupons->fetch_assoc()):
      ?>
        <div class="coupon-card">
          <div class="coupon-logo">
            <img src="uploads/stores/<?php echo $coupon['logo_url']; ?>" alt="<?php echo $coupon['store_name']; ?>">
          </div>
          <div class="coupon-content">
            <h3><?php echo $coupon['title']; ?></h3>
            <p class="desc"><?php echo $coupon['description']; ?></p>
            <div class="coupon-meta">
              <span class="expiry">
                <?php
                $exp = strtotime($coupon['expiry_date']);
                echo $exp ? "Expires: " . date('M d, Y', $exp) : "No expiry";
                ?>
              </span>
            </div>
            <div class="coupon-actions">
              <button class="show-code" onclick="copyCode('<?php echo $coupon['code']; ?>')">Show Code</button>
              <a href="<?php echo $coupon['affiliate_link']; ?>" target="_blank" class="visit-store">Visit Store</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </section>

  <!-- Footer -->
  <footer class="site-footer text-center">
    <p>&copy; <?php echo date("Y"); ?> RatedCoupons. All rights reserved.</p>
  </footer>

  <script>
    function copyCode(code) {
      navigator.clipboard.writeText(code);
      alert("Copied: " + code);
    }
  </script>
</body>
</html>
