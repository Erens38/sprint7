CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    order_date DATE NOT NULL
);

CREATE TABLE order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id)
);

-- Sample Data
INSERT INTO orders (customer_id, order_date) VALUES 
(1, '2024-06-28');

INSERT INTO order_items (order_id, product_id, quantity, price) VALUES 
(1, 101, 2, 20.00),
(1, 102, 1, 10.00);