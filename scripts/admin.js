// Modal Functions
function openModal() {
    document.getElementById("adminModal").style.display = "flex";
    document.getElementById("adminForm").reset();
}

function closeModal() {
    document.getElementById("adminModal").style.display = "none";
    document.getElementById("errorMessage").style.display = "none";
}

// Add Admin Function
function addAdmin() {
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value.trim();
    const confirmPassword = document.getElementById("confirmPassword").value.trim();
    const position = document.getElementById("position").value.trim();
    const errorMessage = document.getElementById("errorMessage");

    if (!username || !password || !position) {
        errorMessage.textContent = "All fields are required!";
        errorMessage.style.display = "block";
        return;
    }

    if (password !== confirmPassword) {
        errorMessage.textContent = "Passwords don't match!";
        errorMessage.style.display = "block";
        return;
    }

    fetch("add_admin.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ username, password, position })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Admin added successfully!");
            closeModal();
            location.reload();
        } else {
            errorMessage.textContent = data.error;
            errorMessage.style.display = "block";
        }
    })
    .catch(error => {
        console.error("Error:", error);
        errorMessage.textContent = "An error occurred";
        errorMessage.style.display = "block";
    });
}

// Delete Functionality
function toggleSaveButton(dropdown, adminId) {
    const saveBtn = document.getElementById(`save-btn-${adminId}`);
    saveBtn.style.display = dropdown.value === "delete" ? "inline-block" : "none";
}

function saveAction(adminId) {
    const dropdown = document.querySelector(`#save-btn-${adminId}`).previousElementSibling.querySelector('.dropdown');
    const action = dropdown.value;

    if (!action || action === "none") return;

    fetch("delete_admin.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ 
            admin_id: adminId, 
            action: action 
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert("Admin status updated successfully!");
            location.reload();
        } else {
            alert(`Error: ${data.error}`);
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Failed to update admin status");
    });
}