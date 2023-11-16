<?php
include 'config1.php';

$tempid = $_GET['sid'];  
$tempnm = $_GET['name']; 
$rollno = $_GET['rollno']; 
// $subid = $_GET['id']; 

if (isset($tempid) && isset($subid)) {
    // Retrieve subject name
    $subjectQuery = "SELECT name FROM subject WHERE id = :subid";
    $subjectStmt = $conn->prepare($subjectQuery);
    $subjectStmt->bindParam(':subid', $subid, PDO::PARAM_INT);
    $subjectStmt->execute();
    $subjectResult = $subjectStmt->fetch(PDO::FETCH_ASSOC);
    $subjectName = $subjectResult['name'];

    // Retrieve attendance details for the specific subject and student
    $attendanceQuery = "SELECT date, grade, comment FROM attendance WHERE sid = :tempid AND id = :subid";
    $attendanceStmt = $conn->prepare($attendanceQuery);
    $attendanceStmt->bindParam(':tempid', $tempid, PDO::PARAM_INT);
    $attendanceStmt->bindParam(':subid', $subid, PDO::PARAM_INT);
    $attendanceStmt->execute();
    $attendanceResult = $attendanceStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $subjectName; ?> Attendance Details</title>
    <style>
        /* Add your CSS styles here */
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo $subjectName; ?> Attendance Details for <?php echo $tempnm; ?> (Roll No: <?php echo $rollno; ?>)</h1>
        <table border="1">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Grade</th>
                    <th>Comment</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($attendanceResult as $attendanceRow) {
                    $date = date("d-m-Y", strtotime($attendanceRow['date']));
                    $grade = $attendanceRow['grade'];
                    $comment = $attendanceRow['comment'];
                ?>
                <tr>
                    <td><?php echo $date; ?></td>
                    <td><?php echo $grade; ?></td>
                    <td><?php echo $comment; ?></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php
} else {
    // Redirect or display an error if student ID or subject ID is not provided
    header("Location: error.php");
}
?>
