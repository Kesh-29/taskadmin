<?php
session_start();
require_once 'db_connection.php'; // Using your db_connection.php

// Check if admin is logged in - redirect to login2.php if not
if (!isset($_SESSION['admin_id'])) {
    header("Location: login2.php");
    exit();
}

// Get admin data from database
$admin_id = $_SESSION['admin_id'];
$query = "SELECT * FROM admins WHERE admin_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// If no admin found, redirect to login
if (!$admin) {
    header("Location: login2.php");
    exit();
}

// Format date of birth if available
$birthDate = isset($admin['birth_date']) ? date('m-d-Y', strtotime($admin['birth_date'])) : '';

// Get address components
$country = isset($admin['country']) ? $admin['country'] : 'Philippines';
$city = isset($admin['city']) ? $admin['city'] : '';
$postalCode = isset($admin['postal_code']) ? $admin['postal_code'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="style/admin_profile.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="sidebar_logo">
        <img src="elements/Main_Logo.png" alt="Task Connect" class="logo">
    </div>
    <div class="main-container">
        <div class="sidebar">
            <nav>
                <ul>
                    <li><a href="admindash.php">Dashboard</a></li>
                    <li><a href="user.php">Users</a></li>
                    <li><a href="tasker_request.php">Tasker<br>Request</a></li>
                    <li><a href="history.php">Req History</a></li>
                    <li><a href="admin.php">Admin <br> Managent</a></li>
                    <li><a href="admin_profile.php">User Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>

        <div class="profile-wrapper">
            <div class="profile-header-section">
                <div class="profile-header">
                    <div class="profile-info">
                        <h1><?php echo htmlspecialchars($admin['first_name'] . ' ' . htmlspecialchars($admin['last_name'])); ?>
                        </h1>
                        <p>Admin</p>
                        <div class="profile-location">
                            <span><?php echo htmlspecialchars($city); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="info-section">
                <h2 class="section-title">Personal Information</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">First Name</div>
                        <div class="info-value" data-field="firstName">
                            <?php echo htmlspecialchars($admin['first_name']); ?>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email Address</div>
                        <div class="info-value" data-field="email"><?php echo htmlspecialchars($admin['email']); ?>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Last Name</div>
                        <div class="info-value" data-field="lastName">
                            <?php echo htmlspecialchars($admin['last_name']); ?>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Phone Number</div>
                        <div class="info-value" data-field="phone"><?php echo htmlspecialchars($admin['mobile_no']); ?>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Date of Birth</div>
                        <div class="info-value" data-field="birthDate"><?php echo htmlspecialchars($birthDate); ?></div>
                    </div>
                </div>
                <button class="edit-btn">Edit</button>
            </div>

            <div class="Address-section">
                <h2 class="section-title">Address</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Country</div>
                        <div class="info-value" data-field="country"><?php echo htmlspecialchars($country); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">City</div>
                        <div class="info-value" data-field="city"><?php echo htmlspecialchars($city); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Portal Code</div>
                        <div class="info-value" data-field="postalCode"><?php echo htmlspecialchars($postalCode); ?>
                        </div>
                    </div>
                </div>
                <button class="edit-btn">Edit</button>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function () {
                const section = this.closest('.info-section, .Address-section');
                const isEditing = this.textContent === 'Save';

                // Toggle all fields in this section
                section.querySelectorAll('.info-value').forEach(field => {
                    if (isEditing) {
                        // Save mode - update the text
                        const input = field.querySelector('input, select');
                        if (input) {
                            field.textContent = input.value;
                        }
                    } else {
                        // Edit mode - create input
                        const fieldName = field.getAttribute('data-field');
                        const currentValue = field.textContent;

                        if (fieldName === 'country' || fieldName === 'city' || fieldName === 'postalCode' ||
                            fieldName === 'firstName' || fieldName === 'lastName' || fieldName === 'email' ||
                            fieldName === 'phone') {
                            field.innerHTML = `<input type="text" value="${currentValue}">`;
                        } else if (fieldName === 'birthDate') {
                            field.innerHTML = `<input type="date" value="${formatDateForInput(currentValue)}">`;
                        }
                    }
                });

                // Toggle button text
                this.textContent = isEditing ? 'Edit' : 'Save';

                // If saving, send data to server
                if (isEditing) {
                    saveProfileData(section);
                }
            });
        });

        function formatDateForInput(dateString) {
            if (!dateString) return '';
            const parts = dateString.split('-');
            if (parts.length === 3) {
                return `${parts[2]}-${parts[0].padStart(2, '0')}-${parts[1].padStart(2, '0')}`;
            }
            return dateString;
        }

        function saveProfileData(section) {
            const formData = new FormData();
            const adminId = <?php echo $admin_id; ?>;
            formData.append('admin_id', adminId);

            // Collect all data from the section
            section.querySelectorAll('.info-value').forEach(field => {
                const fieldName = field.getAttribute('data-field');
                const value = field.querySelector('input') ? field.querySelector('input').value : field.textContent;
                formData.append(fieldName, value);
            });

            // Send data to server
            fetch('update_admin_profile.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Profile updated successfully!');
                        location.reload(); // Reload the page to show updated data
                    } else {
                        alert('Error updating profile: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating profile: ' + error.message);
                });
        }
    </script>
</body>

</html>