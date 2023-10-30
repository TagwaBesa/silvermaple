<?php
session_start();
include('db_connect.php');
include('header.php');

// Check if the student is logged in
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$student_id = $_SESSION['id'];

// You can fetch the student's reports data from your database here
// For this example, we'll assume you have a 'students' table and a 'reports' table
// Adjust the query accordingly to match your database structure
$query = "SELECT r.id, s.id as student_id, s.name as student_name, s.class_id, r.type, r.grade, r.comment, r.date_created
          FROM attendance_record r
          INNER JOIN students s ON r.student_id = s.id
          WHERE r.student_id = '$student_id'";

$result = $conn->query($query);

$reports = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reports</title>
    <style>
    .reports-container {
        margin: 20px;
        padding: 20px;
        background-color: #f5f5f5;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    h2 {
        color: #333;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #ddd;
    }

    a {
        display: inline-block;
        margin-top: 10px;
        padding: 8px 12px;
        background-color: #007BFF;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
    }

    a:hover {
        background-color: #0056b3;
    }
</style>

</head>
<?php include('header.php');?>

<?php include 'topbar.php' ?>
<body>

<main id="view-panel" >

  
<div class="containe-fluid">
	<div class="row mt-3 ml-3 mr-3">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    
               
        <h2><br>Your Reports</h2>
        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Course</th>
                    <th>Status</th>
                    <th>Grade</th>
                    <th>Comment</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reports as $report) : ?>
                    <tr>
                        <td><?php echo $report['student_id']; ?></td>
                        <td><?php echo $report['student_name']; ?></td>
                        <td><?php echo $report['class_id']; ?></td>
                        <td><?php echo $report['type']; ?></td>
                        <td><?php echo $report['grade']; ?></td>
                        <td><?php echo $report['comment']; ?></td>
                        <td><?php echo $report['date_created']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="homepage.php">Back to Homepage</a>
    </div>
    </div>
    </div>      			
        </div>
    </div>
</div>
                </main>
</body>
</html>
