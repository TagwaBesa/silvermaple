<?php
session_start();
include('db_connect.php'); 
include('header.php'); 

if (isset($_POST['login'])) {
    $student_id = $_POST['id'];
    $password = $_POST['password'];

    $query = "SELECT * FROM students WHERE id = '$student_id' AND password = '$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Authentication successful
        $_SESSION['id'] = $student_id;
        header('Location: homepage.php');
        exit();
    } else {
        // Authentication failed
        $error_message = 'Invalid student ID or password. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <style>
	body{
		width: 100%;
	    height: calc(100%);
	    position: fixed;
	    top:0;
	    left: 0
	    /*background: #007bff;*/
	}
	main#main{
		width:100%;
		height: calc(100%);
		display: flex;
	}

</style>
</head>
<body class="bg-dark">
    <div class="login-container">
        <h2>Student Login</h2>
        <?php
        if (isset($error_message)) {
            echo '<p class="error">' . $error_message . '</p>';
        }
        ?>
        <form method="post">
            <label for="student_id">Student ID:</label>
            <input type="text" id="student_id" name="id" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" name="login">Login</button>
        </form>
    </div>
</body>
</html>
