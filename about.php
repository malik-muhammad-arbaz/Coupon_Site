<?php
session_start();
include('../includes/db.php');
include('auth.php'); // Remove if public page

// You can fetch any dynamic content here if needed
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>About Us - SavingsUpscale</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f9fafb;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 80px auto;
            background: #fff;
            padding: 40px 50px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        h1 {
            color: #0077cc;
            text-align: center;
            margin-bottom: 24px;
            font-weight: 700;
        }
        p {
            font-size: 17px;
            line-height: 1.6;
            margin-bottom: 18px;
        }
        a.back-link {
            display: inline-block;
            margin-top: 30px;
            color: #0077cc;
            text-decoration: none;
            font-weight: 600;
        }
        a.back-link:hover {
            color: #005fa3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>About Us</h1>
        <p>Welcome to SavingsUpscale, your trusted source for the best deals, coupons, and discounts online. Our mission is to help savvy shoppers save money effortlessly by providing a curated collection of verified coupons from thousands of popular stores.</p>

        <p>Founded in 2023, we have grown to become one of the most reliable coupon platforms, committed to bringing you the latest and greatest offers every day. Whether you're shopping for electronics, fashion, groceries, or travel, SavingsUpscale is here to make your shopping experience smarter and more affordable.</p>

        <p>Our team works tirelessly to find exclusive deals and update expired coupons so you never miss a chance to save. We value transparency, accuracy, and user satisfaction above all else.</p>

        <p>Thank you for choosing SavingsUpscale. Happy saving!</p>

        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
    </div>
</body>
</html>
