<?php include 'includes/db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Stores - RatedCoupons</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="assets/style.css">
  <style>
    :root {
      --primary: #0077cc;
      --text-dark: #333;
      --bg-light: #f4f6f9;
      --white: #ffffff;
      --shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: var(--bg-light);
      color: var(--text-dark);
    }

    .site-header {
      background: var(--white);
      padding: 15px 0;
      border-bottom: 1px solid #eaeaea;
      box-shadow: var(--shadow);
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .header-container {
      max-width: 1200px;
      margin: auto;
      padding: 0 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }

    .logo a {
      font-size: 28px;
      font-weight: bold;
      color: var(--primary);
      text-decoration: none;
    }

    .main-nav ul {
      list-style: none;
      display: flex;
      gap: 20px;
      margin: 0;
      padding: 0;
    }

    .main-nav a {
      color: var(--text-dark);
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s;
    }

    .main-nav a:hover {
      color: var(--primary);
    }

    .container {
      max-width: 1200px;
      margin: auto;
      padding: 0 20px;
    }

    h2 {
      text-align: center;
      color: var(--primary);
      margin: 40px 0 20px;
      font-size: 32px;
    }

    /* Search Bar */
    .search-bar {
      display: flex;
      justify-content: center;
      margin-bottom: 30px;
      flex-wrap: wrap;
      gap: 10px;
    }

    .search-bar input[type="text"] {
      padding: 12px 16px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 8px;
      width: 100%;
      max-width: 320px;
      transition: box-shadow 0.3s;
    }

    .search-bar input:focus {
      outline: none;
      box-shadow: 0 0 0 3px rgba(0,119,204,0.2);
    }

    .search-bar button {
      padding: 12px 20px;
      background: var(--primary);
      color: var(--white);
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .search-bar button:hover {
      background: #005fa3;
    }

    /* Store Grid */
    .store-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
      gap: 20px;
      padding-bottom: 50px;
      animation: fadeIn 0.4s ease-in;
    }

    .store-card {
      background: var(--white);
      border-radius: 12px;
      padding: 20px 15px;
      text-align: center;
      box-shadow: var(--shadow);
      transition: transform 0.3s ease, box-shadow 0.3s;
      text-decoration: none;
      color: var(--text-dark);
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .store-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    }

    .store-card img {
      max-width: 100px;
      max-height: 60px;
      object-fit: contain;
      margin-bottom: 12px;
      transition: transform 0.3s;
    }

    .store-card:hover img {
      transform: scale(1.05);
    }

    .store-card span {
      font-size: 16px;
      font-weight: 600;
      color: var(--text-dark);
    }

    /* Footer */
    .site-footer {
      background: #f0f0f0;
      text-align: center;
      font-size: 14px;
      color: #666;
      border-top: 1px solid #ddd;
      padding: 30px 20px;
      margin-top: 60px;
    }

    /* Responsive Fixes */
    @media (max-width: 768px) {
      .main-nav ul {
        flex-direction: column;
        align-items: center;
        gap: 10px;
        margin-top: 10px;
      }

      .header-container {
        flex-direction: column;
        align-items: center;
      }

      h2 {
        font-size: 26px;
      }

      .store-card {
        max-width: 100%;
      }
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

<!-- Header -->
<header class="site-header">
  <div class="header-container">
    <div class="logo"><a href="index.php">RatedCoupons</a></div>
    <nav class="main-nav">
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="all-stores.php">Stores</a></li>
        <li><a href="categories.php">Categories</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
    </nav>
  </div>
</header>

<!-- Content -->
<main class="container">
  <h2>🏪 Browse All Stores</h2>

  <!-- Search Bar -->
  <div class="search-bar">
    <form method="GET" action="all-stores.php">
      <input type="text" name="search" placeholder="Search store..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
      <button type="submit">Search</button>
    </form>
  </div>

  <!-- Store Grid -->
  <div class="store-grid">
    <?php
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $query = "SELECT * FROM stores";

    if ($search !== '') {
      $searchSafe = $conn->real_escape_string($search);
      $query .= " WHERE name LIKE '%$searchSafe%'";
    }

    $query .= " ORDER BY name ASC";
    $stores = $conn->query($query);

    if ($stores->num_rows > 0):
      while ($store = $stores->fetch_assoc()):
    ?>
      <a href="store.php?slug=<?php echo urlencode($store['slug']); ?>" class="store-card">
        <img src="uploads/stores/<?php echo htmlspecialchars($store['logo_url']); ?>" alt="<?php echo htmlspecialchars($store['name']); ?> Logo">
        <span><?php echo htmlspecialchars($store['name']); ?></span>
      </a>
    <?php
      endwhile;
    else:
      echo "<p style='text-align:center; width: 100%;'>No stores found.</p>";
    endif;
    ?>
  </div>
</main>

<!-- Footer -->
<footer class="site-footer">
  <p>&copy; <?php echo date('Y'); ?> RatedCoupons. All rights reserved.</p>
</footer>

</body>
</html>
