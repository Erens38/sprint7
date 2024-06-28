<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_item'])) {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];
        $_SESSION['order_items'][] = [
            'product_id' => $product_id,
            'quantity' => $quantity,
            'price' => $price
        ];
    } elseif (isset($_POST['submit_order'])) {
        $customer_id = $_POST['customer_id'];
        $order_date = $_POST['order_date'];
        
        $conn = new mysqli('localhost', 'root', '', 'sales_db');

        $conn->begin_transaction();

        try {
            $stmt = $conn->prepare("INSERT INTO orders (customer_id, order_date) VALUES (?, ?)");
            $stmt->bind_param("is", $customer_id, $order_date);
            $stmt->execute();
            $order_id = $stmt->insert_id;

            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            foreach ($_SESSION['order_items'] as $item) {
                $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
                $stmt->execute();
            }

            $conn->commit();
            $_SESSION['order_items'] = [];
            echo "Order placed successfully!";
        } catch (Exception $e) {
            $conn->rollback();
            echo "Failed to place order: " . $e->getMessage();
        }

        $conn->close();
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Form</title>
</head>
<body>
    <h1>Order Form</h1>
    <form method="post">
        <label for="customer_id">Customer ID:</label>
        <input type="text" id="customer_id" name="customer_id" required><br>
        <label for="order_date">Order Date:</label>
        <input type="date" id="order_date" name="order_date" required><br>
        
        <h2>Add Items</h2>
        <label for="product_id">Product ID:</label>
        <input type="text" id="product_id" name="product_id" required><br>
        <label for="quantity">Quantity:</label>
        <input type="text" id="quantity" name="quantity" required><br>
        <label for="price">Price:</label>
        <input type="text" id="price" name="price" required><br>
        <button type="submit" name="add_item">Add Item</button><br>

        <h2>Order Items</h2>
        <ul>
            <?php if (!empty($_SESSION['order_items'])): ?>
                <?php foreach ($_SESSION['order_items'] as $item): ?>
                    <li>Product ID: <?= htmlspecialchars($item['product_id']) ?>, Quantity: <?= htmlspecialchars($item['quantity']) ?>, Price: <?= htmlspecialchars($item['price']) ?></li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <button type="submit" name="submit_order">Submit Order</button>
    </form>
</body>
</html>