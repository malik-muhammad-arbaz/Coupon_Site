<?php include('auth.php'); ?>
<?php include('../includes/db.php'); ?>
<?php
$latest_stores = $conn->query("SELECT name, created_at FROM stores ORDER BY created_at DESC LIMIT 5");
if (!$latest_stores) {
  die("❌ Query Error (latest_stores): " . $conn->error);
}

$coupon_count = $conn->query("SELECT COUNT(*) AS total FROM coupons")->fetch_assoc()['total'] ?? 0;
$store_count = $conn->query("SELECT COUNT(*) AS total FROM stores")->fetch_assoc()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Coupons & Stores Analytics</title>
  <link rel="stylesheet" href="../assets/style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(120deg, #f4f6f9, #dbe9f4);
      display: flex;
      height: 100vh;
      overflow: hidden;
    }

    .sidebar {
      width: 240px;
      background: #0077cc;
      padding: 30px 20px;
      display: flex;
      flex-direction: column;
      gap: 15px;
      color: #fff;
      box-shadow: 2px 0 12px rgba(0, 0, 0, 0.1);
      overflow-y: auto;
    }

    .sidebar h2 {
      font-size: 24px;
      margin-bottom: 30px;
      text-align: center;
      color: #fff;
    }

    .sidebar a {
      color: #fff;
      text-decoration: none;
      padding: 12px 15px;
      border-radius: 6px;
      background: rgba(255, 255, 255, 0.1);
      transition: all 0.3s ease-in-out;
    }

    .sidebar a:hover {
      background: #fff;
      color: #0077cc;
      transform: scale(1.05);
    }

    .main-content {
      flex: 1;
      padding: 40px;
      background: #fff;
      border-radius: 0 12px 12px 0;
      box-shadow: -4px 0 20px rgba(0, 0, 0, 0.05);
      overflow-y: auto;
      animation: fadeIn 0.5s ease-in-out;
    }

    .main-content h2 {
      font-size: 32px;
      margin-bottom: 20px;
      color: #0077cc;
      border-bottom: 2px solid #eee;
      padding-bottom: 15px;
    }

    .store-list {
      margin-top: 30px;
    }

    .store-list h3 {
      font-size: 22px;
      color: #0077cc;
      margin-bottom: 15px;
    }

    .store-list ul {
      list-style: none;
      padding: 0;
      display: grid;
      gap: 10px;
    }

    .store-list li {
      background: #f1f8ff;
      padding: 15px;
      border-left: 5px solid #0077cc;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.03);
    }

    .store-list li:hover {
      background: #e1f0ff;
    }

    #chart-section {
      margin-top: 40px;
      display: flex;
      flex-direction: column;
      gap: 40px;
      align-items: center;
    }

    .chart-container {
      width: 100%;
      max-width: 800px;
      aspect-ratio: 2 / 1;
    }

    canvas {
      width: 100% !important;
      height: 100% !important;
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>Admin Menu</h2>
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="add-coupon.php">➕ Add Coupon</a>
    <a href="add-store.php">🏬 Add Store</a>
    <a href="add-category.php">📁 Add Category</a>
    <a href="view-coupons.php">🎟️ View Coupons</a>
    <a href="view-stores.php">🏪 View Stores</a>
    <a href="view-categories.php">🗂️ View Categories</a>
    <a href="view-users.php">👥 View Users</a>
    <a href="logout.php">🚪 Logout</a>
  </div>

  <div class="main-content">
    <h2>Store Overview & Charts</h2>

    <div class="store-list">
      <h3>Latest Added Stores</h3>
      <ul>
        <?php while ($store = $latest_stores->fetch_assoc()): ?>
          <li>
            <strong><?= htmlspecialchars($store['name']) ?></strong><br>
            <small>Added on: <?= htmlspecialchars($store['created_at']) ?></small>
          </li>
        <?php endwhile; ?>
      </ul>
    </div>

    <div id="chart-section">
      <div class="chart-container">
        <h3>Coupons vs Stores (Bar)</h3>
        <canvas id="countChart"></canvas>
      </div>

      <div class="chart-container">
        <h3>Coupons vs Stores (Pie)</h3>
        <canvas id="circleChart"></canvas>
      </div>
    </div>

    <script>
      const countCtx = document.getElementById('countChart').getContext('2d');
      const countChart = new Chart(countCtx, {
        type: 'bar',
        data: {
          labels: ['Coupons', 'Stores'],
          datasets: [{
            label: 'Total',
            data: [<?= $coupon_count ?>, <?= $store_count ?>],
            backgroundColor: ['#0077cc', '#00a86b'],
            borderRadius: 10,
            borderSkipped: false
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: { enabled: true }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                stepSize: 500,
                callback: value => value.toLocaleString()
              }
            }
          }
        }
      });

      const pieCtx = document.getElementById('circleChart').getContext('2d');
      const circleChart = new Chart(pieCtx, {
        type: 'doughnut',
        data: {
          labels: ['Coupons', 'Stores'],
          datasets: [{
            label: 'Distribution',
            data: [<?= $coupon_count ?>, <?= $store_count ?>],
            backgroundColor: ['#0077cc', '#00a86b'],
            borderColor: '#fff',
            borderWidth: 2
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { position: 'bottom' },
            tooltip: { callbacks: {
              label: function(context) {
                const label = context.label || '';
                const value = context.raw;
                return `${label}: ${value.toLocaleString()}`;
              }
            }}
          }
        }
      });
    </script>
  </div>
</body>
</html>
