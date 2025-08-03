<?php include 'includes/db.php'; ?>


<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" type="image/x-icon" href="/favicon.ico"> 
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>RatedCoupons - Home</title>
  <style>
    /* ===== GLOBAL BASE STYLES ===== */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    html, body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
      transition: background 0.3s ease, box-shadow 0.3s ease;
      box-shadow: 0 3px 6px rgba(0,119,204,0.4);
    }
    button:hover {
      background: #005fa3;
      box-shadow: 0 5px 15px rgba(0,95,163,0.6);
    }

    /* ===== HEADER & NAVIGATION ===== */
    .site-header {
      background: #fff;
      border-bottom: 1px solid #eaeaea;
      padding: 15px 20px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
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

    /* SEARCH BAR */
    .search-bar {
      margin-left: 20px;
      flex-grow: 1;
      max-width: 300px;
    }
    .search-bar input[type="text"] {
      width: 100%;
      padding: 8px 14px;
      border-radius: 6px;
      border: 1.5px solid #0077cc;
      font-size: 15px;
      transition: border-color 0.3s ease;
    }
    .search-bar input[type="text"]:focus {
      outline: none;
      border-color: #005fa3;
      box-shadow: 0 0 5px rgba(0, 95, 163, 0.5);
    }

    /* ===== HERO SECTION ===== */
    .hero {
      background: linear-gradient(135deg, #0077cc, #00bcd4);
      color: #fff;
      padding: 70px 20px;
      text-align: center;
      box-shadow: inset 0 0 50px rgba(0,0,0,0.1);
      border-radius: 0 0 30px 30px;
    }
    .animated-heading {
      font-size: 36px;
      margin-bottom: 12px;
      animation: fadeInUp 1s ease;
      text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
    }
    @keyframes fadeInUp {
      0% {opacity: 0; transform: translateY(20px);}
      100% {opacity: 1; transform: translateY(0);}
    }

    /* ===== CATEGORIES, STORES, COUPONS ===== */
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
      box-shadow: 0 3px 8px rgba(0,119,204,0.15);
    }
    .category-btn:hover {
      background: #0077cc;
      color: #fff;
      box-shadow: 0 6px 20px rgba(0,119,204,0.4);
    }

    /* COUPON GRID */
    .coupon-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 24px;
      margin-top: 30px;
    }

    /* COUPON CARD */
    .coupon-card {
      background: #fff;
      border-radius: 12px;
      padding: 18px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.07);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      border: 1px solid #ddd;
    }
    .coupon-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.12);
    }

    /* STORE LOGO ALIGNMENT */
    .coupon-logo {
      text-align: center;
      margin-bottom: 12px;
    }
    .coupon-logo img {
      max-height: 80px;
      width: auto;
      margin: 0 auto;
      display: block;
      object-fit: contain;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      transition: transform 0.3s ease;
    }
    .coupon-logo img:hover {
      transform: scale(1.05);
      box-shadow: 0 6px 20px rgba(0,0,0,0.2);
    }

    .coupon-content h3 {
      font-size: 20px;
      margin-bottom: 8px;
      color: #0077cc;
      font-weight: 700;
    }

    .coupon-content h3 a {
      text-decoration: none;
      transition: color 0.3s ease;
    }
    .coupon-content h3 a:hover {
      color: #005fa3;
      text-decoration: underline;
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
      font-style: italic;
    }

    .coupon-actions {
      display: flex;
      justify-content: space-between;
      gap: 10px;
      flex-wrap: wrap;
    }

    .show-code {
      background-color: #ff9800;
      box-shadow: 0 3px 10px rgba(255,152,0,0.5);
    }
    .show-code:hover {
      background-color: #e68900;
      box-shadow: 0 5px 20px rgba(230,137,0,0.7);
    }

    .visit-store {
      background-color: #4caf50;
      color: #fff;
      padding: 10px 14px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 600;
      box-shadow: 0 3px 10px rgba(76,175,80,0.5);
      transition: background-color 0.3s ease, box-shadow 0.3s ease;
      display: inline-block;
      text-align: center;
    }
    .visit-store:hover {
      background-color: #3e8e41;
      box-shadow: 0 5px 20px rgba(62,142,65,0.7);
    }

    /* ===== FOOTER ===== */
    .site-footer {
      background: #f0f0f0;
      padding: 30px 0;
      font-size: 14px;
      text-align: center;
      color: #666;
      border-top: 1px solid #ddd;
      margin-top: 50px;
    }

    /* ===== RESPONSIVE ===== */
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
      .search-bar {
        max-width: 100%;
        margin: 12px 0 0 0;
      }
      .container {
        padding: 0 10px;
      }
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header class="site-header">
    <div class="container flex flex-center space-between">
      <h1 class="logo"><a href="index.php">RatedCoupons</a></h1>

      <nav class="main-nav flex flex-center" style="gap: 24px;">
        <ul class="flex">
          <li><a href="index.php">Home</a></li>
          <li><a href="all-stores.php">Stores</a></li>
          <li><a href="categories.php">Categories</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="contact.php">Contact</a></li>
        </ul>

        <!-- Search Bar -->
        <form class="search-bar" action="search.php" method="GET" role="search" aria-label="Site search">
          <input type="text" name="q" placeholder="Search coupons or stores..." aria-label="Search coupons or stores" />
        </form>
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
          <button class="category-btn"><?php echo htmlspecialchars($cat['name']); ?></button>
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
      <img src="uploads/stores/<?php echo htmlspecialchars($s['logo_url']); ?>" alt="<?php echo htmlspecialchars($s['name']); ?> Logo" />
    </div>
    <div class="coupon-content text-center">
      <h3>
        <a href="/store/<?php echo urlencode($s['slug']); ?>">
          <?php echo htmlspecialchars($s['name']); ?>
        </a>
      </h3>
      <?php if (!empty($s['tagline'])): ?>
        <p class="desc"><?php echo htmlspecialchars($s['tagline']); ?></p>
      <?php endif; ?>
      <a href="/store/<?php echo urlencode($s['slug']); ?>" class="visit-store">View Coupons</a>
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
            <img src="uploads/stores/<?php echo htmlspecialchars($coupon['logo_url']); ?>" alt="<?php echo htmlspecialchars($coupon['store_name']); ?>" />
          </div>
          <div class="coupon-content">
            <h3><?php echo htmlspecialchars($coupon['title']); ?></h3>
            <p class="desc"><?php echo htmlspecialchars($coupon['description']); ?></p>
            <div class="coupon-meta">
              <span class="expiry">
                <?php
                $exp = strtotime($coupon['expiry_date']);
                echo $exp ? "Expires: " . date('M d, Y', $exp) : "No expiry";
                ?>
              </span>
            </div>
            <div class="coupon-actions">
              <button class="show-code" onclick="copyCode('<?php echo addslashes($coupon['code']); ?>')">Show Code</button>
              <a href="<?php echo htmlspecialchars($coupon['affiliate_link']); ?>" target="_blank" rel="noopener" class="visit-store">Visit Store</a>
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
      navigator.clipboard.writeText(code).then(() => {
        alert("Copied: " + code);
      }).catch(() => {
        alert("Failed to copy code. Please copy manually.");
      });
    }
  </script>
</body>
</html>
