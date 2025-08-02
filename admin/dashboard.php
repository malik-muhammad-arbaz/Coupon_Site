<?php include('auth.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../assets/style.css">
  <style>
    .admin-dashboard {
      max-width: 800px;
      margin: 60px auto;
      padding: 40px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
      text-align: center;
    }

    .admin-dashboard h2 {
      font-size: 32px;
      margin-bottom: 30px;
      color: #0077cc;
      border-bottom: 2px solid #eee;
      padding-bottom: 15px;
    }

    .dashboard-links {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 20px;
      margin-top: 20px;
    }

    .dashboard-links a {
      display: block;
      background: #f4f6f9;
      color: #0077cc;
      font-weight: 600;
      padding: 14px;
      border: 1px solid #ddd;
      border-radius: 8px;
      transition: 0.3s ease;
      text-decoration: none;
    }

    .dashboard-links a:hover {
      background: #0077cc;
      color: #fff;
      border-color: #0077cc;
    }

    body.dark .admin-dashboard {
      background: #2a2a2a;
      color: #ddd;
    }

    body.dark .dashboard-links a {
      background: #444;
      color: #ddd;
      border-color: #555;
    }

    body.dark .dashboard-links a:hover {
      background: #0077cc;
      color: #fff;
    }
  </style>
</head>
<body>

  <div class="admin-dashboard">
    <h2>Admin Dashboard</h2>

    <div class="dashboard-links">
      <a href="add-coupon.php">Add Coupon</a>
      <a href="add-store.php">Add Store</a>
      <a href="add-category.php">Add Category</a>
      <a href="view-coupons.php">View Coupons</a>
      <a href="view-stores.php">View Stores</a>
      <a href="view-categories.php">View Categories</a>
      <a href="view-users.php">View Users</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>

</body>
</html>
