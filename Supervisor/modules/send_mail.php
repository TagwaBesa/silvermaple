<?php
include 'config1.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

// Include PHPMailer files
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Fetch absent students and the corresponding subjects they missed
$absentStudents = getAbsentStudents($conn);

// Send emails to absent students
sendEmailsToAbsentStudents($absentStudents, $conn);

function getAbsentStudents($conn) {
    $absentStudents = [];

    // Modify the query to get students who have been absent more than 3 times
    $sqlAbsent = "SELECT s.name, s.email, a.id as subject_id
                  FROM student s
                  JOIN attendance a ON s.sid = a.sid
                  WHERE a.ispresent = 0
                  GROUP BY s.sid, a.id
                  HAVING COUNT(*) < 3";

    $stmtAbsent = $conn->prepare($sqlAbsent);
    $stmtAbsent->execute();
    $absentStudents = $stmtAbsent->fetchAll(PDO::FETCH_ASSOC);

    return $absentStudents;
}

function sendEmailsToAbsentStudents($absentStudents, $conn) {
    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    // Set up email parameters
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 465;
    $mail->SMTPSecure = 'ssl';
    $mail->SMTPAuth = true;
    $mail->Username = 'besaemmanuel99@gmail.com';

    // Your Gmail password or App Password
    $mail->Password = 'uzlj bakf ufhd nanx';

    // Set the 'From' email address
    $mail->setFrom('besaemmanuel99@gmail.com', 'Besa');
    $mail->Subject = 'Attendance Warning';

    foreach ($absentStudents as $student) {
        $name = $student['name'];
        $email = $student['email'];
        $subjectId = $student['subject_id'];

        // Get subject name based on subject ID (Assuming subjects are identified by an 'id' in the database)
        $subjectName = getSubjectName($conn, $subjectId);

        // Add a recipient for each absent student
        $mail->addAddress($email, $name);

        // Email body
        $mail->Body = "Dear $name,\n\nYou have been absent more than three times in the subject '$subjectName'. Please take necessary actions to improve your attendance.";

        // Send the email
        if ($mail->send()) {
            echo "Email sent successfully to $name ($email).<br>";
        } else {
            echo "Email could not be sent to $name ($email). Mailer Error: " . $mail->ErrorInfo . "<br>";
        }

        // Clear recipients for the next iteration
        $mail->clearAddresses();
    }
}

function getSubjectName($conn, $subjectId) {
    // Modify the query to get the subject name based on subject ID
    $sqlSubject = "SELECT name FROM subject WHERE id = :subjectId";
    $stmtSubject = $conn->prepare($sqlSubject);
    $stmtSubject->bindParam(':subjectId', $subjectId, PDO::PARAM_INT);
    $stmtSubject->execute();
    $subject = $stmtSubject->fetch(PDO::FETCH_ASSOC);

    return $subject ? $subject['name'] : 'Unknown Subject';
}
?>
