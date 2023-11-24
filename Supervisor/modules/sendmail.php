<?php
include 'config1.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function checkAttendanceAndNotify($conn)
{
    // Query to get students who missed a class
    $query = "SELECT a.sid, a.id, COUNT(*) AS missed_count 
              FROM attendance a
              WHERE a.ispresent = 0 
              GROUP BY a.sid, a.id";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
        $studentId = $result['sid'];
        $subjectId = $result['id'];
        $missedCount = $result['missed_count'];

        if ($missedCount ==4) {
            $studentInfo = getStudentInfo($conn, $studentId, $subjectId);

            if (!empty($studentInfo)) {
                $studentName = $studentInfo['student_name'];
                $subjectName = $studentInfo['subject_name'];
                $studentEmail = $studentInfo['email'];

                // Create a new PHPMailer instance
                $mail = initializeMailer();

                // Add the student's email address
                $mail->addAddress($studentEmail, $studentName);

                // Email subject
                $mail->Subject = "Missed Class Notification - $subjectName";

                // Email body
                $emailBody = generateEmailBody($studentName, $subjectName, $missedCount);
                $mail->Body = $emailBody;

                // Send the email
                if (sendEmail($mail)) {
                    echo "Email sent successfully for student: $studentName (Missed $missedCount classes in $subjectName)\n";
                } else {
                    echo "Email could not be sent for student: $studentName\n";
                }
            }
        }
    }
}

// Rest of the functions remain the same as in the previous code snippet

checkAttendanceAndNotify($conn);
?>
