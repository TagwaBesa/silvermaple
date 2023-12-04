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

    $sqlAbsent = "SELECT s.name, s.email, a.id AS subject_id, COUNT(a.id) AS absence_count
                  FROM student s
                  JOIN attendance a ON s.sid = a.sid
                  WHERE a.ispresent = 0
                  GROUP BY s.sid, a.id
                  HAVING absence_count < 4";

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

    foreach ($absentStudents as $originalStudent) {
        $student = $originalStudent;
        $name = $student['name'];
        $email = $student['email'];
        $subjectId = $student['subject_id'];
        $absenceCount = $student['absence_count'];

        // Check if an email has already been sent to this student for this subject
        if (!hasEmailBeenSent($conn, $email, $subjectId)) {
            // Get subject name based on subject ID
            $subjectName = getSubjectName($conn, $subjectId);

            // Add a recipient for each absent student
            $mail->addAddress($email, $name);

            // Email body
            $mailBody = generateEmailBody($name, $subjectName, $absenceCount);
            $mail->Body = $mailBody;

            // Send the email
            if (sendEmail($mail)) {
                echo "Email sent successfully to $name ($email).<br>";

                // Log that an email has been sent to this student for this subject
                logEmailSent($conn, $email, $subjectId);
            } else {
                echo "Email could not be sent to $name ($email). Mailer Error: " . $mail->ErrorInfo . "<br>";
            }

            // Clear recipients for the next iteration
            $mail->clearAddresses();
        } else {
            echo "Email already sent to $name ($email) for subject $subjectId.<br>";
        }
    }
}

function hasEmailBeenSent($conn, $email, $subjectId) {
  // Check if an email has been sent to this student for this subject
  $sqlCheckSent = "SELECT COUNT(*) AS email_count FROM email_log WHERE email = :email AND id = :subjectId";
  $stmtCheckSent = $conn->prepare($sqlCheckSent);
  $stmtCheckSent->bindParam(':email', $email, PDO::PARAM_STR);
  $stmtCheckSent->bindParam(':subjectId', $subjectId, PDO::PARAM_INT);
  $stmtCheckSent->execute();
  $emailCount = $stmtCheckSent->fetch(PDO::FETCH_ASSOC)['email_count'];

  return $emailCount >= 4; // Return true if the email has been sent three times
}


function logEmailSent($conn, $email, $subjectId) {
  // Log that an email has been sent to this student for this subject
  $sqlLogSent = "INSERT INTO email_log (email, id, send_count) VALUES (:email, :subjectId, 1)
                  ON DUPLICATE KEY UPDATE send_count = send_count + 1";
  $stmtLogSent = $conn->prepare($sqlLogSent);
  $stmtLogSent->bindParam(':email', $email, PDO::PARAM_STR);
  $stmtLogSent->bindParam(':subjectId', $subjectId, PDO::PARAM_INT);

  if ($stmtLogSent->execute()) {
    //   echo "Email logged successfully for $email and subject $subjectId.<br>";
  } else {
      echo "Error logging email for $email and subject $subjectId.<br>";
      print_r($stmtLogSent->errorInfo());
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

function generateEmailBody($name, $subjectName, $absenceCount) {
    // Generate email body based on absence count
    $emailBody = "Dear $name,\n\n";
    if ($absenceCount === 1) {
        $emailBody .= "You have been absent once in the subject '$subjectName'. Please make sure to attend your classes regularly to avoid falling behind.";
    } elseif ($absenceCount === 2) {
        $emailBody .= "You have been absent twice in the subject '$subjectName'. Please take immediate action to improve your attendance.";
    } elseif ($absenceCount===3){
        $emailBody .= "This is your final warning. You have been absent three times in the subject '$subjectName'. Failure to improve your attendance will result in disciplinary action.";
    }

    return $emailBody;
}

function sendEmail($mail) {
    // Send the email
    try {
        return $mail->send();
    } catch (PHPMailerException $e) {
        return false;
    }
}
?>
