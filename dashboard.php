<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: login.html");
  exit;
}

// Database Connection
$servername = "localhost";
$username = "root";
$password = ""; // Change to your database password
$dbname = "12_04_2024_webdev";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// If form is submitted, insert note into database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $title = $_POST['title'];
  $description = $_POST['description'];
  $user_email = $_SESSION['email']; // Retrieve user email from session

  // Prepare and execute SQL statement to insert note into user_notes table
  $sql = "INSERT INTO user_notes (title, description, user_email) VALUES (?, ?, ?)";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "sss", $title, $description, $user_email);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
}

// Retrieve user's name from another table based on email
$user_email = $_SESSION['email'];
$sql = "SELECT name FROM 12_04_2024_webdev_table WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $user_email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $user_name = $row['name'];
} else {
    // Default name if not found
    $user_name = "Guest";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="dashboard.css">
</head>

<body class="img-fluid" alt="..." background="img14.jpg">
  <div class="container">
  <h2>Welcome, <i style="color: rgb(207, 200, 213);"> <?php echo $user_name; ?></i></h2>

<p><b>This is Your Dashboard. You are logged in.</b></p>

<h2> Here You Can Create Your Notes For Memery </h2>

<form method="POST">
  <div class="mb-3">
    <label for="title" class="form-label">Title</label>
    <input type="text" class="form-control" id="title" name="title">
  </div>
  <div class="form-group">
    <label for="description">Description</label>
    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>

<h2>Your Notes:</h2>
<?php
// Retrieve user's notes from database using email
$user_email = $_SESSION['email'];
$sql = "SELECT * FROM user_notes WHERE user_email = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $user_email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    echo "<div class='card mb-3'>";
    echo "<div class='card-body'>";
    echo "<h5 class='card-title'>" . $row['title'] . "</h5>";
    echo "<p class='card-text'>" . $row['description'] . "</p>";
    echo "</div>";
    echo "</div>";
  }
} else {
  echo "You have no notes yet.";
}
?>
<button type="submit" class="text-center my-2 mx-4 btn btn-primary "><a href="logout.php"><strong ><i style="background: rgb(4, 0, 3);">Logout</i></strong></a></button>


  </div>
  
</body>

</html>
