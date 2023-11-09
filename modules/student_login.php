<?php
session_start();
include 'config1.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rollno = $_POST['rollno'];
    $password = $_POST['password'];

    $query = "SELECT * FROM student WHERE rollno = :rollno AND password = :password";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':rollno', $rollno, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // Student is authenticated, set a session variable
        $_SESSION['student_id'] = $result['sid'];
        header('Location: studentdata.php'); // Redirect to the attendance report page
    } else {
        // Invalid login, display an error message or redirect back to the login page
        header('Location: student_login.php?error=1');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Your head content here -->
</head>
<body>
    <h1>Student Login</h1>
    <form method="POST" action="student_login.php">
        <label for="rollno">Roll Number:</label>
        <input type="text" name="rollno" id="rollno" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br>

        <input type="submit" value="Login">
    </form>
</body>
</html>

