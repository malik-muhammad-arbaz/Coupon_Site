<?php
session_start();
include('../includes/db.php');
include('auth.php');

// Approve admin
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    $conn->query("UPDATE admins SET is_approved = 1 WHERE id = $id");
    header("Location: view-users.php");
    exit();
}

// Delete admin
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM admins WHERE id = $id");
    header("Location: view-users.php");
    exit();
}

// Fetch admins
$users = $conn->query("SELECT * FROM admins ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Users</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .sidebar {
            width: 220px;
            background: #0077cc;
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            color: #fff;
            box-shadow: 2px 0 12px rgba(0, 0, 0, 0.1);
        }

        .sidebar h2 {
            font-size: 22px;
            margin-bottom: 20px;
            text-align: center;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease-in-out;
            font-weight: bold;
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
            font-size: 30px;
            margin-bottom: 20px;
            color: #0077cc;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 14px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
            font-weight: 600;
            color: #333;
        }

        tr:hover {
            background-color: #f0f8ff;
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

        .action-buttons .delete {
            background-color: #e53935;
        }

        .action-buttons .delete:hover {
            background-color: #c62828;
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
    <h2>All Admin Users</h2>

    <?php if ($users && $users->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Approved</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $users->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo $user['is_approved'] ? '✅' : '❌'; ?></td>
                        <td class="action-buttons">
                            <?php if (!$user['is_approved']): ?>
                                <a href="view-users.php?approve=<?php echo $user['id']; ?>">✅ Approve</a>
                            <?php endif; ?>
                            <a href="view-users.php?delete=<?php echo $user['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this user?')">🗑️ Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>
</div>
</body>
</html>
