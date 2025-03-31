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
                // Edit mode - create input fields
                const fieldName = field.getAttribute('data-field');
                const currentValue = field.textContent.trim();

                // Map field names for consistency with PHP and database
                const fieldMap = {
                    "first_name": "firstName",
                    "last_name": "lastName",
                    "email": "email",
                    "mobile_no": "phone",
                    "birth_date": "birthDate",
                    "country": "country",
                    "city": "city",
                    "postal_code": "postalCode"
                };

                if (fieldMap[fieldName]) {
                    field.innerHTML = `<input type="text" value="${currentValue}" name="${fieldMap[fieldName]}">`;
                } else if (fieldName === 'birth_date') {
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

// Function to format date
function formatDateForInput(dateString) {
    if (!dateString) return '';
    const parts = dateString.split('-');
    if (parts.length === 3) {
        return `${parts[0]}-${parts[1].padStart(2, '0')}-${parts[2].padStart(2, '0')}`;
    }
    return dateString;
}

// Function to send profile data to PHP
function saveProfileData(section) {
    const formData = new FormData();
    const adminId = document.getElementById("admin_id").value;
    formData.append('admin_id', adminId);

    section.querySelectorAll('.info-value').forEach(field => {
        const fieldName = field.getAttribute('data-field');
        const input = field.querySelector('input');
        if (input) {
            formData.append(fieldName, input.value);
        } else {
            formData.append(fieldName, field.textContent.trim());
        }
    });

    fetch('update_admin_profile.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Profile updated successfully!');
            location.reload();
        } else {
            alert('Error updating profile: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating profile: ' + error.message);
    });
}
