<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    try {
        $sql = "DELETE FROM students WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() > 0) {
            header("Location: ../index.php?status=success&section=delete");
        } else {
            header("Location: ../index.php?status=invalid_id&section=delete");
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
