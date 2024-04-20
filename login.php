<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Prepare SQL statement to fetch user from database
    $sql = "SELECT * FROM 12_04_2024_webdev_table WHERE email = ? AND password = ?  ";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $email, $password );
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if user exists in database
    if (mysqli_num_rows($result) == 1) {
        // User exists, set session variables and redirect to their respective dashboard
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $email;
        header("Location: dashboard.php");
        exit;
    } else {
        // Invalid details, redirect back to login page
        header("Location: login.html");
        exit;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
