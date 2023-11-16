<?php
include 'config1.php';

$present = 0;
$absent = 0;
$nottaken = 0;
$ttaken = 0;
$strno = $_POST['rollno'];

if (isset($_POST['rollno'])) {
    $rollno = $_POST['rollno'];

    // Retrieve the student ID based on the provided roll number
    $sql = "SELECT name, sid, rollno FROM student WHERE rollno = :rollno";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':rollno', $rollno, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($result)) {
        $tempnm = $result[0]['name'];
        $tempid = $result[0]['sid'];
        $rollno = $result[0]['rollno'];

        $sqlAbsent = "SELECT ispresent FROM attendance WHERE sid = :sid AND ispresent = 0";
        $stmtAbsent = $conn->prepare($sqlAbsent);
        $stmtAbsent->bindParam(':sid', $tempid, PDO::PARAM_INT);
        $stmtAbsent->execute();
        $absentCount = $stmtAbsent->rowCount();
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
                                $timestamp = $result2[$k]['date'];
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
                                    <h6><a href="modules/subject_details.php?id=<?php echo $subid; ?>"><?php echo $sub; ?></a></h6>
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
                                            echo " <span class='badge'>Not Taken</span>";
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
// Create a line graph for two subjects for the specified student
$subjectId1 = 3; // Replace with the actual subject ID for the first subject
$subjectId2 = 2; // Replace with the actual subject ID for the second subject

// Fetch data for the first subject
$query1 = "SELECT date, grade FROM attendance
          WHERE id = :subjectId1 AND sid = :studentId
          ORDER BY date";
$stmt1 = $conn->prepare($query1);
$stmt1->bindParam(':subjectId1', $subjectId1, PDO::PARAM_INT);
$stmt1->bindParam(':studentId', $studentId, PDO::PARAM_INT);
$stmt1->execute();
$result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

// Fetch data for the second subject
$query2 = "SELECT date, grade FROM attendance
          WHERE id = :subjectId2 AND sid = :studentId
          ORDER BY date";
$stmt2 = $conn->prepare($query2);
$stmt2->bindParam(':subjectId2', $subjectId2, PDO::PARAM_INT);
$stmt2->bindParam(':studentId', $studentId, PDO::PARAM_INT);
$stmt2->execute();
$result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// Combine data for both subjects
$graphData = [
    'subject1' => [],
    'subject2' => [],
];

foreach ($result1 as $row) {
    $graphData['subject1'][] = [
        'date' => $row['date'],
        'grade' => $row['grade'],
    ];
}

foreach ($result2 as $row) {
    $graphData['subject2'][] = [
        'date' => $row['date'],
        'grade' => $row['grade'],
    ];
}
?>
<script>
    // Use PHP to convert PHP data to JavaScript
    var graphData = <?php echo json_encode($graphData); ?>;

    // Extract dates and grades from the data for each subject
    var subject1Data = graphData.subject1;
    var subject2Data = graphData.subject2;

    // Convert timestamps to date strings for the x-axis labels
    var labels1 = subject1Data.map(function (dataPoint) {
        return new Date(dataPoint.date * 1000).toLocaleDateString();
    });
    var labels2 = subject2Data.map(function (dataPoint) {
        return new Date(dataPoint.date * 1000).toLocaleDateString();
    });

    var data1 = subject1Data.map(function (dataPoint) {
        return dataPoint.grade;
    });

    var data2 = subject2Data.map(function (dataPoint) {
        return dataPoint.grade;
    });

    var ctx = document.getElementById('attendanceGraph').getContext('2d');

    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels1,
            datasets: [{
                label: 'Subject 1',
                data: data1,
                fill: true,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }, {
                label: 'Subject 2',
                data: data2,
                fill: true,
                borderColor: 'rgb(192, 75, 192)',
                tension: 0.1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    max: 10 // Set your maximum grade here
                }
            }
        }
    });
</script>
