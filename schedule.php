<?php
// Include your database connection code here
include 'db_connect.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_id = $_POST['class_id'];
    $week_number = $_POST['week_number'];

    // Perform validation if needed

    // Insert data into the class_schedule table
    $sql = "INSERT INTO class_schedule (class_id, week_number) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ii", $class_id, $week_number);
        if ($stmt->execute()) {
            echo "Class schedule added successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Schedule</title>
    <!-- Add any necessary CSS or JS files for your class schedule page -->
</head>
<body>
<?php include 'topbar.php'; ?>
<main id="view-panel">
    <div class="container-fluid">
    <h2>Add Class Schedule</h2>

<form method="POST" action="save_schedule.php">
    <label for="class_id">Class or Course ID:</label>
    <select name="class_subject_id" id="class_subject_id" class="custom-select select2 input-sm">
				                <option value=""></option>
				                <?php
				                $class = $conn->query("SELECT cs.*,concat(co.course,' ',c.level,'-',c.section) as `class`,s.subject,f.name as fname FROM class_subject cs inner join `class` c on c.id = cs.class_id inner join courses co on co.id = c.course_id inner join faculty f on f.id = cs.faculty_id inner join subjects s on s.id = cs.subject_id ".($_SESSION['login_faculty_id'] ? " where f.id = {$_SESSION['login_faculty_id']} ":"")." order by concat(co.course,' ',c.level,'-',c.section) asc");
				                while($row=$class->fetch_assoc()):
				                ?>
				                <option value="<?php echo $row['id'] ?>" data-cid="<?php echo $row['id'] ?>" <?php echo isset($class_subject_id) && $class_subject_id == $row['id'] ? 'selected' : (isset($class_subject_id) && $class_subject_id == $row['id'] ? 'selected' :'') ?>><?php echo $row['class'].' '.$row['subject']. ' [ '.$row['fname'].' ]' ?></option>
				                <?php endwhile; ?>
				            </select>

    <label for="week_number">Week Number:</label>
    <input type="text" name="week_number" id="week_number" required>

    <input type="submit" value="Add Schedule">
</form>
    </div>
</main>
</body>
</html>
