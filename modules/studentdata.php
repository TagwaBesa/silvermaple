<?php

include 'config1.php';
require 'sendmail.php';

// Check if the student is not logged in, then redirect to the login page

$present = 0;
$absent = 0;
$nottaken = 0;
$ttaken = 0;

// Retrieve the student ID, replace '1' with the actual source to get the student ID
$studentId = 1; // Replace with the actual student ID retrieval method

// Student data collection
$sql = "SELECT name, sid, rollno FROM student where sid = :studentId"; // Modify the query to use student ID
$stmt = $conn->prepare($sql);
$stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($result)) {
    $tempnm = $result[0]['name'];
    $tempid = $result[0]['sid'];
    $rollno = $result[0]['rollno'];


    $sqlAbsent = "SELECT ispresent FROM attendance WHERE sid = :studentId AND ispresent = 0";
    $stmtAbsent = $conn->prepare($sqlAbsent);
    $stmtAbsent->bindParam(':studentId', $studentId, PDO::PARAM_INT);
    $stmtAbsent->execute();
    $absentCount = $stmtAbsent->rowCount();
    
    if ($absentCount >=3) {
        // Echo a message once the student has been absent 3 or more times
        echo "Warning! You have been absent 3 or more times.";
        checkAttendanceAndNotify($conn);
    }
    

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <h1 class="page-header"><?php print $tempnm; ?> - <?php print $rollno; ?> Attendance Report</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <?php
            if ($_POST['student'] === 'y' && isset($_POST['rollno'])) {
                $sq = "SELECT DISTINCT date FROM attendance ORDER BY date";
                $stmt2 = $conn->prepare($sq);
                $stmt2->execute();
                $result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <table class='table table-striped table-hover reports-table'>
                <tr>
                    <th>Subject</th>
                    <?php
                    for ($k = 0; $k < count($result2); $k++) {
                        $tmdat = $result2[$k]['date'];
                        // Convert the date string to a Unix timestamp
                        $timestamp = strtotime($tmdat);
                    ?>
                    <th><?php echo date("d-m-Y", $timestamp); ?></th>
                    <?php
                    }
                    
                    ?>
                    <th>Total</th>
                    <th>Grade</th>
                    <th colspan='2'>%</th>
                </tr>
                <?php
                $ssql = "SELECT id FROM student_subject where $tempid=sid";
                $stmt3 = $conn->prepare($ssql);
                $stmt3->execute();
                $result3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);

                foreach ($result3 as $subRow) {
                    $dpresent = 0;
                    $dabsent = 0;
                    $dnottaken = 0;
                    $dttaken = 0;
                    $subid = $subRow['id'];
                    $sqql = "SELECT name FROM subject where $subid=id";
                    $stmt4 = $conn->prepare($sqql);
                    $stmt4->execute();
                    $result4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);
                    $sub = $result4[0]['name'];
                ?>
                <tr>
                    <td>
                        <h6><a href="subject_details.php?id=<?php echo $subid; ?>"><?php echo $sub; ?></a></h6>
                    </td>
                    <?php
                    for ($i = 0; $i < count($result2); $i++) {
                        $tmdat = $result2[$i]['date'];
                        $sql1 = "SELECT ispresent FROM attendance where sid=$tempid AND id=$subid AND date=$tmdat ORDER BY date";
                        $stmt1 = $conn->prepare($sql1);
                        $stmt1->execute();
                        $result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
                        $ttaken++;
                        $dttaken++;
                    ?>
                    <td>
                        <?php
                        if (empty($result1)) {
                            echo " <span class='badge';'>Not Taken</span>";
                            $nottaken++;
                            $dnottaken++;
                        } else {
                            $res = $result1[0]['ispresent'];
                            if ($res == 1) {
                                echo " <span class='badge' style='background-color:#3C923C;'>Present</span>";
                                $present++;
                                $dpresent++;
                            } else {
                                echo "<span class='text-danger'>Absent</span>";
                                $absent++;
                                $dabsent++;
                            }
                        }
                        ?>
                    </td>
                    <?php
                    }
                    $dtlec = $dttaken - $dnottaken;
                    $dtper = ($dtlec != 0) ? round((100 * $dpresent) / $dtlec, 2) : 0;
                    ?>

                    <td><strong><?php echo $dpresent; ?></strong>/<?php echo $dtlec; ?></td>
                    <td>
                        <?php
                        $totalGrade = 0; // Initialize total grade
                        $totalGradePossible = 0; // Initialize total possible grade
                        for ($i = 0; $i < count($result2); $i++) {
                            $tmdat = $result2[$i]['date'];
                            $sql1 = "SELECT ispresent, grade FROM attendance WHERE sid=$tempid AND id=$subid AND date=$tmdat ORDER BY date";
                            $stmt1 = $conn->prepare($sql1);
                            $stmt1->execute();
                            $result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
                            if (!empty($result1)) {
                                $res = $result1[0]['ispresent'];
                                $grade = $result1[0]['grade'];
                                if ($res == 1) {
                                    $totalGrade += $grade; // Add the grade earned
                                    $totalGradePossible += 5; // Add 5 to the total possible grade
                                }
                            }
                        }
                        // Display the total grade as a fraction out of the total possible grade
                        echo $totalGrade . '/' . $totalGradePossible;
                        ?>
                    </td>
                    <td><?php echo $dtper; ?>&nbsp;%</td>
                </tr>
                <?php
                }
                ?>
            </table>

            <?php
            $tlec = $ttaken - $nottaken;
            $tper = round((100 * $present) / $tlec, 2);
 

            ?>
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Summary Of Attendance</h3>
                </div>
                <div class="panel-body">
                    <p>Present Days out of All Days:&nbsp;<strong><?php echo $present; ?>/<?php echo $tlec; ?></strong></p>
                    <p>Attendance Percentage:&nbsp;<strong><?php echo $tper; ?>&nbsp;%</strong></p>
                </div>
            </div>
            <?php
            } else {
                header("location:index.php?student=invalid");
            }
            ?>
        </div>
    </div>
</div>
<canvas id="attendanceGraph" width="200" height="100"></canvas>
</body>
</html>
<?php
} else {
    header("location:index.php?student=invalid");
}
?>

<?php
// Create a line graph for the specified subject and student
$subjectId = 2; // Replace with the actual subject ID
$query = "SELECT date, grade FROM attendance
          WHERE id = :subjectId AND sid = :studentId
          ORDER BY date";
$stmt = $conn->prepare($query);
$stmt->bindParam(':subjectId', $subjectId, PDO::PARAM_INT);
$stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$graphData = [];

foreach ($result as $row) {
    $graphData[] = [
        'date' => $row['date'],
        'grade' => $row['grade'],
    ];
}
?>

<script>
// Use PHP to convert PHP data to JavaScript
var graphData = <?php echo json_encode($graphData); ?>;

// Extract dates and grades from the data
var labels = graphData.map(function(dataPoint) {
    return dataPoint.date;
});
var data = graphData.map(function(dataPoint) {
    return dataPoint.grade;
});

var ctx = document.getElementById('attendanceGraph').getContext('2d');

var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Subject',
            data: data,
            fill: false,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                max: 6 // Set your maximum grade here
            }
        }
    }
});
</script>
