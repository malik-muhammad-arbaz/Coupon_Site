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

$stores = $conn->query("SELECT * FROM stores ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Stores</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .admin-dashboard {
            max-width: 1000px;
            margin: auto;
            padding: 20px;
        }

        .store-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            overflow: hidden;
        }

        .store-table th,
        .store-table td {
            padding: 12px 14px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        .store-table th {
            background-color: #f5f5f5;
            font-weight: 600;
            color: #333;
        }

        .store-table tr:hover {
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
    </style>
</head>
<body>
    <div class="admin-dashboard">
        <h2>All Stores</h2>

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
                                <a href="edit-store.php?id=<?php echo $s['id']; ?>">✏️ Edit</a>
                                <a href="view-stores.php?delete=<?php echo $s['id']; ?>" onclick="return confirm('Are you sure you want to delete this store?')">🗑️ Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No stores found.</p>
        <?php endif; ?>

        <p><a href="dashboard.php">← Back to Dashboard</a></p>
    </div>
</body>
</html>
