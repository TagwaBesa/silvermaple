<?php
// index.php

// Include necessary configurations and start session
include 'config1.php';

// Check if the user is logged in
if(isset($_SESSION['sid'])) {
    // Get the user ID from the session
    $userId = $_SESSION['sid'];

    // TODO: Perform a database query to retrieve the student's name and roll number based on the user ID
    // Assuming you have a PDO connection named $pdo
    $query = "SELECT name, rollno FROM student WHERE sid = :sid";
    $stmt = $conn->prepare($query);

    // Bind the parameter
    $stmt->bindParam(':sid', $userId, PDO::PARAM_INT);

    // Execute the query
    $stmt->execute();

    // Fetch the result
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the query was successful
    if ($row) {
        // Display the welcome message with both name and roll number
        $studentName = $row['name'];
        $rollNumber = $row['rollno'];
        echo "Welcome, $studentName! Your Roll Number is $rollNumber.";
    } else {
        // Handle the query error
        echo "Error retrieving student information.";
    }
} else {
    // Redirect to the login page if the user is not logged in
    header("Location: login.php");
    exit();
}


	$todayYMD = date("Y-m-d");
	$today = date("d/m/Y");
	$todayQuery = date("d-m-Y");
	$todayTimestamp = strtotime($today);
	$userId = $_SESSION['sid'];
?>

<div class="row">
	<div class="col-lg-5">
		<div class="panel panel-danger">
			<div class="panel-heading">
			  <h3 class="panel-title text-center"><b>Enter your Roll Number</b></h3>
			</div>
			<div class="panel-body text-center">
			<form class="form-horizontal" action="index.php" method="post" id="studentForm" data-toggle="validator">
        <div class="form-group">
          <label for="rollno" class="control-label">Roll no.</label>
    

<input type="number" class="form-control" id="rollno" maxlength="6" name="rollno" value="<?php echo $rollNumber; ?>" placeholder="Please Enter Student's Roll Number" required readonly>
 
				
				<div class="form-group">
					<input type="submit" name="submit" class="btn 	" style="border-radius:0%" value="View Reports	">
				</div>
				
				<input type="hidden" name="student" value="y" />
      </form>
				  
				 
				 	</div>

	<div class="col-lg-3">
		
	</div>
</div>



