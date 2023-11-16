<?php
include 'config1.php';

// Assuming subject ID is passed through the URL parameter 'id'
$subjectId = isset($_GET['id']) ? $_GET['id'] : 0;

// Fetch subject details
$sqlSubject = "SELECT name FROM subject WHERE id = :subjectId";
$stmtSubject = $conn->prepare($sqlSubject);
$stmtSubject->bindParam(':subjectId', $subjectId, PDO::PARAM_INT);
$stmtSubject->execute();
$subjectResult = $stmtSubject->fetch(PDO::FETCH_ASSOC);

if (!$subjectResult) {
    echo "Subject not found!";
    exit;
}

$subjectName = $subjectResult['name'];

// Fetch attendance details for the selected subject
$sqlAttendance = "SELECT date, grade, comment FROM attendance
                  WHERE id = :subjectId
                  ORDER BY date";
$stmtAttendance = $conn->prepare($sqlAttendance);
$stmtAttendance->bindParam(':subjectId', $subjectId, PDO::PARAM_INT);
$stmtAttendance->execute();
$attendanceResult = $stmtAttendance->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject Details - <?php echo $subjectName; ?></title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Subject Details - <?php echo $subjectName; ?></h1>

    <table>
        <tr>
            <th>Date</th>
            <th>Grade</th>
            <th>Comment</th>
        </tr>
        <?php
        foreach ($attendanceResult as $row) {
            $date = date("d-m-Y", strtotime($row['date']));
            $grade = $row['grade'];
            $comment = $row['comment'];
        ?>
        <tr>
            <td><?php echo $date; ?></td>
            <td><?php echo $grade; ?></td>
            <td><?php echo $comment; ?></td>
        </tr>
        <?php
        }
        ?>
    </table>

</body>
</html>
