<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $surname = $_POST['surname'];
    $name = $_POST['name'];
    $middlename = $_POST['middlename'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];

    try {
        $sql = "INSERT INTO students (surname, name, middlename, address, contact_number) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$surname, $name, $middlename, $address, $contact]);
        header("Location: ../index.php?status=success&section=create");
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
