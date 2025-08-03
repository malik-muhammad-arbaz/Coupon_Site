<?php
include('includes/db.php');

// Fetch all stores
$store_result = $conn->query("SELECT id, name, logo_url FROM stores ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Stores</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        h2 {
            text-align: center;
            color: #0077cc;
            margin-bottom: 30px;
        }

        .store-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 24px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .store-card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 8px 16px rgba(0,0,0,0.06);
            transition: transform 0.2s ease;
        }

        .store-card:hover {
            transform: translateY(-4px);
        }

        .store-card img {
            max-height: 60px;
            margin-bottom: 10px;
        }

        .store-card a {
            text-decoration: none;
            color: #333;
            font-weight: 600;
            display: block;
            margin-top: 10px;
        }

        .store-card a:hover {
            color: #0077cc;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 40px;
            font-weight: 600;
            color: #0077cc;
            text-decoration: none;
        }

        .back-link:hover {
            color: #005fa3;
        }
    </style>
</head>
<body>

    <h2>🏬 All Stores</h2>

    <div class="store-grid">
        <?php if ($store_result->num_rows > 0): ?>
            <?php while ($store = $store_result->fetch_assoc()): ?>
                <div class="store-card">
                    <img src="<?php echo htmlspecialchars($store['logo_url']); ?>" alt="<?php echo htmlspecialchars($store['name']); ?>">
                    <a href="store/<?php echo $store['slug']; ?>">
                        <?php echo htmlspecialchars($store['name']); ?>
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center;">No stores found.</p>
        <?php endif; ?>
    </div>

    <a href="index.php" class="back-link">← Back to Home</a>

</body>
</html>
