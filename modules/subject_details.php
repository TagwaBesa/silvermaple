<?php
include 'config1.php';

if (isset($_GET['subject_id'])) {
    $subjectId = $_GET['subject_id'];

    // Query to retrieve subject details based on subjectId
    $sql = "SELECT name, description FROM subject WHERE id = :subject_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':subject_id', $subjectId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $subjectName = $result['name'];
        $subjectDescription = $result['description'];
        // Display subject details
        echo "<h1>Subject Details</h1>";
        echo "<p><strong>Name:</strong> $subjectName</p>";
        echo "<p><strong>Description:</strong> $subjectDescription</p>";
    } else {
        echo "Subject not found.";
    }
} else {
    echo "Subject ID not provided.";
}
?>
