<?php
session_start();
include('../includes/db.php');
include('auth.php');

// Handle delete action
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM categories WHERE id = $id");
    header("Location: view-categories.php");
    exit();
}

// Handle edit action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'], $_POST['edit_name'])) {
    $edit_id = intval($_POST['edit_id']);
    $edit_name = $conn->real_escape_string(trim($_POST['edit_name']));
    if (!empty($edit_name)) {
        $conn->query("UPDATE categories SET name = '$edit_name' WHERE id = $edit_id");
    }
    header("Location: view-categories.php");
    exit();
}

// Fetch categories
$categories = $conn->query("SELECT * FROM categories ORDER BY id DESC");
if (!$categories) {
    die("❌ Query Error (categories): " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Categories</title>
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

        .edit-form {
            display: flex;
            gap: 10px;
        }

        .edit-form input[type="text"] {
            padding: 6px;
            width: 150px;
        }

        .edit-form button {
            padding: 6px 10px;
            background: #0077cc;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .edit-form button:hover {
            background: #005fa3;
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
    <h2>All Categories</h2>

    <?php if ($categories->num_rows > 0): ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <form method="POST" class="edit-form">
                                <input type="hidden" name="edit_id" value="<?php echo $cat['id']; ?>">
                                <input type="text" name="edit_name" value="<?php echo htmlspecialchars($cat['name']); ?>">
                                <button type="submit">Save</button>
                            </form>
                        </td>
                        <td>
                            <a href="view-categories.php?delete=<?php echo $cat['id']; ?>" onclick="return confirm('Are you sure you want to delete this category?');" style="color:red">🗑️ Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No categories found.</p>
    <?php endif; ?>
</div>

</body>
</html>