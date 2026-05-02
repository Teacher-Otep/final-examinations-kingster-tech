<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $surname = $_POST['surname'];
    $name = $_POST['name'];
    $middlename = $_POST['middlename'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];

    try {
        $sql = "UPDATE students SET surname = ?, name = ?, middlename = ?, address = ?, contact_number = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$surname, $name, $middlename, $address, $contact, $id]);
        
        if ($stmt->rowCount() > 0) {
            header("Location: ../index.php?status=success&section=update");
        } else {
            header("Location: ../index.php?status=success&section=update");
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
