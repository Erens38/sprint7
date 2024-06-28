<?php
$conn = new mysqli('localhost', 'root', '', 'sales_db');

$sql = "SELECT o.order_id, o.customer_id, o.order_date, 
        oi.product_id, oi.quantity, oi.price 
        FROM orders o 
        JOIN order_items oi ON o.order_id = oi.order_id";
$result = $conn->query($sql);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
</head>
<body>
    <h1>Order Management</h1>
    <table border="1">
        <tr>
            <th>Order ID</th>
            <th>Customer ID</th>
            <th>Order Date</th>
            <th>Product ID</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['order_id']) ?></td>
                    <td><?= htmlspecialchars($row['customer_id']) ?></td>
                    <td><?= htmlspecialchars($row['order_date']) ?></td>
                    <td><?= htmlspecialchars($row['product_id']) ?></td>
                    <td><?= htmlspecialchars($row['quantity']) ?></td>
                    <td><?= htmlspecialchars($row['price']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">No orders found</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>