<?php
session_start();
include('db_connect.php'); // Include or instantiate the database connection

include('header.php'); 

// Check if the student is logged in
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$student_id = $_SESSION['id'];
//$student_name = $_SESSION['name'];

// Fetch the student's name using their student ID
$query = "SELECT name FROM students WHERE id = '$student_id'";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $student_name = $row['name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Homepage</title>
   
</head>
<body>
<?php include 'topbar.php' ?>
<main id="view-panel" >
<div class="container-fluid">
    <div>
   
        
        <h2>Welcome, <?php echo $student_name; ?>!</h2>
        <p>Your Student ID: <?php echo $student_id; ?></p>
        <a href="view_report.php">View Report</a>
        <a href="logout.php">Logout</a>
    </div>
    </div>
</main>
</body>
</html>


