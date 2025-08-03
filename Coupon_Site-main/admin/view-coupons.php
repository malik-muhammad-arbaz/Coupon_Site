<?php
session_start();
include('../includes/db.php');
include('auth.php');

// Handle delete action
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM coupons WHERE id = $id");
    header("Location: view-coupons.php");
    exit();
}

// Handle search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {
    $stmt = $conn->prepare("SELECT c.*, s.name as store_name FROM coupons c LEFT JOIN stores s ON c.store_id = s.id WHERE s.name LIKE CONCAT('%', ?, '%') ORDER BY c.created_at DESC");
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $coupons = $stmt->get_result();
} else {
    $coupons = $conn->query("SELECT c.*, s.name as store_name FROM coupons c LEFT JOIN stores s ON c.store_id = s.id ORDER BY c.created_at DESC");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Coupons</title>
    <link rel="stylesheet" href="../assets/style.css">
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

        .search-box {
            margin-bottom: 20px;
        }

        .search-box input[type="text"] {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        .search-box button {
            padding: 10px 14px;
            border: none;
            background: #0077cc;
            color: #fff;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
        }

        .search-box button:hover {
            background: #005fa3;
        }

        table.admin-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table.admin-table th, table.admin-table td {
            border: 1px solid #ddd;
            padding: 12px 15px;
            text-align: left;
        }

        table.admin-table th {
            background: #f1f1f1;
            color: #333;
        }

        table.admin-table tr:hover {
            background-color: #f9f9f9;
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
    <h2>All Coupons</h2>

    <form method="GET" class="search-box">
        <input type="text" name="search" placeholder="Search by store name" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">🔍 Search</button>
    </form>

    <?php if ($coupons->num_rows > 0): ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Store</th>
                    <th>Code</th>
                    <th>Expiry</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($c = $coupons->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($c['title']); ?></td>
                        <td><?php echo htmlspecialchars($c['store_name']); ?></td>
                        <td><?php echo htmlspecialchars($c['code']); ?></td>
                        <td><?php echo $c['expiry_date'] ? date('M d, Y', strtotime($c['expiry_date'])) : 'N/A'; ?></td>
                        <td>
                            <a href="edit-coupon.php?id=<?php echo $c['id']; ?>">✏️ Edit</a> |
                            <a href="view-coupons.php?delete=<?php echo $c['id']; ?>" onclick="return confirm('Delete this coupon?')">🗑️ Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No coupons found for this store.</p>
    <?php endif; ?>
</div>

</body>
</html>
