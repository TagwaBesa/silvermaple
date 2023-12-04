<?php
include 'config1.php';
include 'header.php';

// Assuming you have the subject ID and roll number from the URL parameters
$subid = $_GET['id'];
$rollno = $_GET['rollno'];

// Retrieve subject name
$subjectQuery = "SELECT name FROM subject WHERE id = :subid";
$subjectStmt = $conn->prepare($subjectQuery);
$subjectStmt->bindParam(':subid', $subid, PDO::PARAM_INT);
$subjectStmt->execute();
$subjectResult = $subjectStmt->fetch(PDO::FETCH_ASSOC);
$subjectName = $subjectResult['name'];

// Retrieve student details
$studentQuery = "SELECT name, sid FROM student WHERE rollno = :rollno";
$studentStmt = $conn->prepare($studentQuery);
$studentStmt->bindParam(':rollno', $rollno, PDO::PARAM_INT);
$studentStmt->execute();
$studentResult = $studentStmt->fetch(PDO::FETCH_ASSOC);
$studentName = $studentResult['name'];
$studentId = $studentResult['sid'];

// Retrieve attendance details for the specific subject and student
$attendanceQuery = "SELECT date, grade, ispresent, comment, time FROM attendance WHERE sid = :studentId AND id = :subid";
$attendanceStmt = $conn->prepare($attendanceQuery);
$attendanceStmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
$attendanceStmt->bindParam(':subid', $subid, PDO::PARAM_INT);
$attendanceStmt->execute();
$attendanceResult = $attendanceStmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate the total number of classes and classes attended
$totalClasses = count($attendanceResult);
$attendedClasses = array_reduce($attendanceResult, function ($carry, $item) {
    return $carry + ($item['ispresent'] == 1 ? 1 : 0);
}, 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $subjectName; ?> Attendance Details</title>
    <style>
        /* Add your CSS styles here */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            margin-top: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo $subjectName; ?> Attendance Details for <?php echo $studentName; ?> (Roll No: <?php echo $rollno; ?>)</h1>
        <p>Total Classes Recorded: <?php echo $totalClasses; ?></p>
        <p>Classes Attended: <?php echo $attendedClasses; ?></p>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Grade</th>
                    <th>Comment</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($attendanceResult as $attendanceRow) {
                    if ($attendanceRow['ispresent'] == 1) {
                        $date = date("d-m-Y", date($attendanceRow['date']));
                        $presence = 'Present';
                        $comment = $attendanceRow['comment'];
                        $grade = $attendanceRow['grade'];
                        $time = date("H:i", strtotime($attendanceRow['time']));
                ?>
                <tr>
                    <td><?php echo $date; ?></td>
                    <td><?php echo $grade; ?></td>
                    <td><?php echo $comment; ?></td>
                    <td><?php echo $time; ?></td>
                </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

