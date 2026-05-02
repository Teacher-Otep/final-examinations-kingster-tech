function showSection(sectionID) {
    const sections = document.querySelectorAll('.content');
    const homeSection = document.getElementById('home');

    // Hide all content sections and the home section
    sections.forEach(section => {
        section.style.display = 'none';
    });
    if (homeSection) homeSection.style.display = 'none';

    // Show the requested section
    const activeSection = document.getElementById(sectionID);
    if (activeSection) {
        activeSection.style.display = 'block';

        // Refresh data if it's read section
        if (sectionID === 'read') loadStudents();
        // Clear input and hide form for update section when entering
        if (sectionID === 'update') {
            document.getElementById('updateForm').style.display = 'none';
            document.getElementById('update_id_input').value = '';
        }
        if (sectionID === 'delete') {
            document.getElementById('delete_id_input').value = '';
        }
    }
}

// Logo mouse event: hide all content sections when clicked
document.getElementById('logo').addEventListener('click', function () {
    const sections = document.querySelectorAll('.content');
    sections.forEach(section => {
        section.style.display = 'none';
    });
    // Optionally show home section again
    const homeSection = document.getElementById('home');
    if (homeSection) homeSection.style.display = 'block';
});

// Function to clear fields
function clearFields(formId) {
    const form = document.getElementById(formId);
    if (form) {
        const inputs = form.querySelectorAll('input[type="text"], input[type="number"]');
        inputs.forEach(input => input.value = '');
    }
}

// Load all students for the Read section
function loadStudents() {
    fetch('index.php?action=get_students')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('studentTableBody');
            tableBody.innerHTML = '';
            if (data.error) {
                console.error(data.error);
                return;
            }
            data.forEach(student => {
                const row = `<tr>
                    <td>${student.id}</td>
                    <td>${student.name}</td>
                    <td>${student.surname}</td>
                    <td>${student.middlename || ''}</td>
                    <td>${student.address || ''}</td>
                    <td>${student.contact_number || ''}</td>
                </tr>`;
                tableBody.innerHTML += row;
            });
        });
}

// Custom Modal Logic
function showModal(title, message, onConfirm = null) {
    const modal = document.getElementById('customModal');
    document.getElementById('modalTitle').innerText = title;
    document.getElementById('modalMessage').innerText = message;

    const confirmBtn = document.getElementById('modalConfirm');
    const cancelBtn = document.getElementById('modalCancel');

    modal.style.display = 'flex';

    confirmBtn.onclick = () => {
        if (onConfirm) onConfirm();
        modal.style.display = 'none';
    };

    cancelBtn.onclick = () => {
        modal.style.display = 'none';
    };

    if (!onConfirm) {
        cancelBtn.style.display = 'none';
    } else {
        cancelBtn.style.display = 'inline-block';
    }
}

// Global Enter Key Listener for Modal
document.addEventListener('keydown', (e) => {
    const modal = document.getElementById('customModal');
    if (modal && modal.style.display === 'flex' && e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('modalConfirm').click();
    }
});

// Intercept Form Submissions
document.addEventListener('DOMContentLoaded', () => {
    const forms = {
        'createForm': 'Are you sure you want to save this student?',
        'updateForm': 'Are you sure you want to update this student record?',
        'deleteForm': 'Are you sure you want to delete this student record?'
    };

    Object.keys(forms).forEach(formId => {
        const form = document.getElementById(formId);
        if (form) {
            form.onsubmit = (e) => {
                e.preventDefault();
                showModal('Confirm Action', forms[formId], () => {
                    form.submit();
                });
            };
        }
    });
});

// Load specific student data into Update form
function loadStudentData(id) {
    if (!id) {
        showModal('Error', "Please enter a Student ID");
        document.getElementById('updateForm').style.display = 'none';
        return;
    }
    fetch(`index.php?action=get_student_by_id&id=${id}`)
        .then(response => response.json())
        .then(student => {
            if (student && !student.error && student.id) {
                document.getElementById('update_id').value = student.id;
                document.getElementById('update_surname').value = student.surname;
                document.getElementById('update_name').value = student.name;
                document.getElementById('update_middlename').value = student.middlename || '';
                document.getElementById('update_address').value = student.address || '';
                document.getElementById('update_contact').value = student.contact_number || '';
                document.getElementById('updateForm').style.display = 'block';
            } else {
                showModal('Error', "Student ID not found.");
                document.getElementById('updateForm').style.display = 'none';
                document.getElementById('update_id_input').value = ''; // Reset input
            }
        });
}

// For the operation success
window.onload = function () {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('status') === 'success') {
        showModal('Success', "Operation Successful! The student record has been updated.");
        // Clean the URL
        window.history.replaceState({}, document.title, window.location.pathname);
    } else if (urlParams.get('status') === 'invalid_id') {
        showModal('Error', "Invalid ID! No student record found with that ID.");
        const deleteInput = document.getElementById('delete_id_input');
        if (deleteInput) deleteInput.value = ''; // Reset input
        // Clean the URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }
    // Determine which section to show (default to home)
    const sectionToShow = urlParams.get('section') || 'home';
    showSection(sectionToShow);
}