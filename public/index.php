<?php
include 'includes/db.php';

// Handle API requests
if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    if ($_GET['action'] == 'get_students') {
        try {
            $stmt = $conn->prepare("SELECT * FROM students");
            $stmt->execute();
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
        exit;
    }
    if ($_GET['action'] == 'get_student_by_id' && isset($_GET['id'])) {
        try {
            $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav class="navbar">
        <img src="images/logo.png" id="logo" alt="Logo">
        <button class="navbarbuttons" onclick="showSection('create')"> Create </button>
        <button class="navbarbuttons" onclick="showSection('read')"> Read </button>
        <button class="navbarbuttons" onclick="showSection('update')"> Update </button>
        <button class="navbarbuttons" onclick="showSection('delete')"> Delete </button>
    </nav>

    <section id="home" class="homecontent">
        <h1 class="splash">Student Management System</h1>
        <h2 class="subtitle">A Project in Integrative Programming Technologies</h2>
    </section>

    <section id="create" class="content" style="display:none;">
        <h1 class="contenttitle"> Insert New Student </h1>
        <form action="includes/insert.php" method="POST" id="createForm">
            <label for="surname" class="label">Surname</label>
            <input type="text" name="surname" id="surname" class="field" required><br />

            <label for="name" class="label">Name</label>
            <input type="text" name="name" id="name" class="field" required><br />

            <label for="middlename" class="label">Middle name</label>
            <input type="text" name="middlename" id="middlename" class="field"><br />

            <label for="address" class="label">Address</label>
            <input type="text" name="address" id="address" class="field"><br />

            <label for="contact" class="label">Mobile Number</label>
            <input type="tel" name="contact" id="contact" class="field" oninput="this.value = this.value.replace(/[^0-9]/g, '')"><br />

            <div id="btncontainer">
                <button type="button" id="clrbtn" class="btns" onclick="clearFields('createForm')">Clear Fields</button><br />
                <button type="submit" id="savebtn" class="btns">Save</button>
            </div>
        </form>

    </section>

    <section id="read" class="content" style="display:none;">
        <h1 class="contenttitle"> View Students </h1>
        <div id="studentTableContainer">
            <!-- Student data will be loaded here -->
            <table id="studentTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Surname</th>
                        <th>Middle Name</th>
                        <th>Address</th>
                        <th>Contact</th>
                    </tr>
                </thead>
                <tbody id="studentTableBody">
                    <!-- Rows will be injected by JS -->
                </tbody>
            </table>
        </div>
    </section>

    <section id="update" class="content" style="display:none;">
        <h1 class="contenttitle"> Update Student Records </h1>
        <div id="selection-area">
            <label for="update_id_input" class="label">Enter Student ID</label>
            <input type="number" id="update_id_input" class="field" placeholder="Type ID here" onkeydown="if(event.key === 'Enter') { if(document.getElementById('customModal').style.display !== 'flex') { event.preventDefault(); loadStudentData(this.value); } }">
            <button type="button" class="btns" style="width: 80px; margin-left: 10px;" onclick="loadStudentData(document.getElementById('update_id_input').value)">Load</button>
        </div>
        <form action="includes/update.php" method="POST" id="updateForm" style="display:none; margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px;">
            <input type="hidden" name="id" id="update_id">
            <label for="update_surname" class="label">Surname</label>
            <input type="text" name="surname" id="update_surname" class="field" required><br />

            <label for="update_name" class="label">Name</label>
            <input type="text" name="name" id="update_name" class="field" required><br />

            <label for="update_middlename" class="label">Middle name</label>
            <input type="text" name="middlename" id="update_middlename" class="field"><br />

            <label for="update_address" class="label">Address</label>
            <input type="text" name="address" id="update_address" class="field"><br />

            <label for="update_contact" class="label">Mobile Number</label>
            <input type="tel" name="contact" id="update_contact" class="field" oninput="this.value = this.value.replace(/[^0-9]/g, '')"><br />

            <div id="btncontainer">
                <button type="button" class="btns" onclick="clearFields('updateForm')">Clear Fields</button><br />
                <button type="submit" class="btns">Update</button>
            </div>
        </form>
    </section>

    <section id="delete" class="content" style="display:none;">
        <h1 class="contenttitle"> Remove Student Records </h1>
        <form action="includes/delete.php" method="POST" id="deleteForm">
            <label for="delete_id_input" class="label">Enter Student ID</label>
            <input type="number" name="id" id="delete_id_input" class="field" required placeholder="Type ID here"><br />
            <div id="btncontainer">
                <button type="submit" class="btns" style="background-color: #f44336; color: white;">Delete Student</button>
            </div>
        </form>
    </section>

    <!-- Custom Modal -->
    <div id="customModal" class="modal-overlay" style="display:none;">
        <div class="modal-box">
            <h3 id="modalTitle">Notification</h3>
            <p id="modalMessage"></p>
            <div class="modal-footer">
                <button id="modalCancel" class="btns btn-secondary">Cancel</button>
                <button id="modalConfirm" class="btns">OK</button>
            </div>
        </div>
    </div>


    <script src="script.js"></script>
</body>

</html>