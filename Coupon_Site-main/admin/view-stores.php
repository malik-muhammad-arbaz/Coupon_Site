<?php
session_start();
include('../includes/db.php');
include('auth.php');

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM stores WHERE id = $id");
    header("Location: view-stores.php");
    exit();
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {
    $stmt = $conn->prepare("SELECT * FROM stores WHERE name LIKE CONCAT('%', ?, '%') ORDER BY created_at DESC");
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $stores = $stmt->get_result();
} else {
    $stores = $conn->query("SELECT * FROM stores ORDER BY created_at DESC");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Stores</title>
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

        table.store-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: #fff;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            overflow: hidden;
        }

        table.store-table th,
        table.store-table td {
            padding: 12px 14px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        table.store-table th {
            background-color: #f5f5f5;
            font-weight: 600;
            color: #333;
        }

        table.store-table tr:hover {
            background-color: #f0f8ff;
        }

        .store-logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 4px;
            background-color: #fafafa;
        }

        .action-buttons a {
            margin-right: 10px;
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            background-color: #0077cc;
            color: #fff;
            transition: background-color 0.3s;
        }

        .action-buttons a:hover {
            background-color: #005fa3;
        }

        .action-buttons a:last-child {
            background-color: #e53935;
        }

        .action-buttons a:last-child:hover {
            background-color: #c62828;
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
    <h2>All Stores</h2>

    <form method="GET" class="search-box">
        <input type="text" name="search" placeholder="Search by store name" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">🔍 Search</button>
    </form>

    <?php if ($stores->num_rows > 0): ?>
        <table class="store-table">
            <thead>
                <tr>
                    <th>Logo</th>
                    <th>Name</th>
                    <th>Website</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($s = $stores->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?php if ($s['logo_url']): ?>
                                <img src="../uploads/stores/<?php echo $s['logo_url']; ?>" alt="Logo" class="store-logo">
                            <?php else: ?>
                                No Logo
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($s['name']); ?></td>
                        <td><a href="<?php echo htmlspecialchars($s['website_url']); ?>" target="_blank"><?php echo htmlspecialchars($s['website_url']); ?></a></td>
                        <td class="action-buttons">
                            <a href="store/<?php echo $store['slug']; ?>">
                            <a href="view-stores.php?delete=<?php echo $s['id']; ?>" onclick="return confirm('Are you sure you want to delete this store?')">🗑️ Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No stores found.</p>
    <?php endif; ?>
</div>
</body>
</html>
