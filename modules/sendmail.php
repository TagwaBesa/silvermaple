<?php
 include 'config1.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Function to check attendance count and notify via email
function checkAttendanceAndNotify($conn)
{
    // Define the threshold (3 times)
    $threshold = 3;

    // Query to count students' attendance for each subject
    $query = "SELECT sid, id, COUNT(*) AS attendance_count 
              FROM attendance 
              WHERE ispresent = 1 
              GROUP BY sid, id 
              HAVING COUNT(*) >= :threshold";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':threshold', $threshold, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($results)) {
        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);

        // Use Gmail with SMTP
        $mail->isSMTP();

        // Set the hostname of the mail server
        $mail->Host = 'smtp.gmail.com';

        // Set the SMTP port number - 587 for TLS, 465 for SSL
        $mail->Port = 465;

        // Enable TLS encryption
        $mail->SMTPSecure = 'ssl';

        // Enable SMTP authentication
        $mail->SMTPAuth = true;

        // Your Gmail username (your full Gmail email address)
        $mail->Username = 'besaemmanuel99@gmail.com';

        // Your Gmail password or App Password
        $mail->Password = 'uzlj bakf ufhd nanx';

        // Set the 'From' email address
        $mail->setFrom('besaemmanuel99@gmail.com', 'Besa');

        foreach ($results as $result) {
            $studentId = $result['sid'];
            $subjectId = $result['id'];
            $attendanceCount = $result['attendance_count'];

            // Query to get the student's name and subject name
            $studentQuery = "SELECT student.name AS student_name, subject.name AS subject_name 
                             FROM student 
                             JOIN subject ON student_subject.id = subject.id 
                             WHERE student.sid = :student_id AND subject.id = :subject_id";
            
            $studentStmt = $conn->prepare($studentQuery);
            $studentStmt->bindParam(':student_id', $studentId, PDO::PARAM_INT);
            $studentStmt->bindParam(':subject_id', $subjectId, PDO::PARAM_INT);
             // $studentStmt->execute();
            $studentResult = $studentStmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($studentResult)) {
                $studentName = $studentResult['student_name'];
                $subjectName = $studentResult['subject_name'];
                $emailBody = "Student $studentName has been present for at least $attendanceCount times in $subjectName.";

                // Add a recipient
                $mail->addAddress('1804685@northrise.net', 'Emmanuel');

                // Email subject
                $mail->Subject = 'Subject of your email';

                // Email body
                $mail->Body = $emailBody;

                // Send the email
                if ($mail->send()) {
                    echo 'Email sent successfully for student: ' . $studentName . '<br>';
                } else {
                    echo 'Email could not be sent for student: ' . $studentName . '<br>';
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                }
            }
        }
    }
}
?>

<!-- ###################################################################################### -->


