<?php
include 'config1.php';
require 'sendmail.php';

if (isset($_GET['attendance_id']) && isset($_GET['subject_id'])) {
    $attendanceId = $_GET['attendance_id'];
    $subjectId = $_GET['subject_id'];

    $sql = "SELECT date, grade, comment FROM attendance WHERE id = :subjectId AND sid = :studentId AND date = :attendanceId";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':subjectId', $subjectId, PDO::PARAM_INT);
    $stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
    $stmt->bindParam(':attendanceId', $attendanceId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($result)) {
        $attendanceDate = date("Y-m-d", $result[0]['date']);
        $grade = $result[0]['grade'];
        $comment = $result[0]['comment'];

        // Display the details
        echo "Attendance Date: $attendanceDate <br>";
        echo "Grade: $grade <br>";
        echo "Comment: $comment <br>";
    } else {
        echo "No data found for the specified attendance and subject.";
    }
} else {
    echo "Please provide both the attendance ID and subject ID.";
}
?>
