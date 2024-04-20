<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Database Connection
    $servername = "localhost";
    $username = "root";
    $dbpassword = "";
    $dbname = "12_04_2024_webdev";

    // Create connection
    $conn = mysqli_connect($servername, $username, $dbpassword, $dbname);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare and bind SQL statement
    $stmt = $conn->prepare("INSERT INTO 12_04_2024_webdev_table (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New record created successfully";
        // Redirect browser
        header("Location: index.html");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    mysqli_close($conn);
}
?>
