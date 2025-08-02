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

// Fetch all coupons with store names
$coupons = $conn->query("SELECT c.*, s.name as store_name FROM coupons c LEFT JOIN stores s ON c.store_id = s.id ORDER BY c.created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Coupons</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="admin-dashboard">
        <h2>All Coupons</h2>

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
            <p>No coupons available.</p>
        <?php endif; ?>

        <p><a href="dashboard.php">← Back to Dashboard</a></p>
    </div>
</body>
</html>
