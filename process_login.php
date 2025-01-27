<?php
include('db_config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process login form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check in the clients table
    $clientQuery = "SELECT * FROM clients WHERE username='$username'";
    $clientResult = $conn->query($clientQuery);

    // Check in the customers table if not found in the clients table
    if ($clientResult->num_rows === 0) {
        $customerQuery = "SELECT * FROM customers WHERE username='$username'";
        $customerResult = $conn->query($customerQuery);

        if ($customerResult->num_rows > 0) {
            $row = $customerResult->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                // Login successful for customer, redirect to customer.php
                header("Location: customer.php?name=" . $row['name'] . "&email=" . $row['email'] . "&phone=" . $row['phone']);
                exit();
            } else {
                echo "Invalid password!";
            }
        } else {
            echo "User not found!";
        }
    } else {
        $row = $clientResult->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Login successful for client, redirect to client.php
            header("Location: client.php?name=" . $row['name'] . "&email=" . $row['email'] . "&phone=" . $row['phone']);
            exit();
        } else {
            echo "Invalid password!";
        }
    }
}

$conn->close();
?>
