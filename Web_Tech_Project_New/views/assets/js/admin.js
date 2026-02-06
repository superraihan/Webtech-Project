function showSection(sectionId, element) {
    const sections = document.querySelectorAll('.section-content');
    sections.forEach(section => {
        section.classList.remove('active');
    });

    const buttons = document.querySelectorAll('.nav-btn');
    buttons.forEach(btn => {
        btn.classList.remove('active');
    });

    document.getElementById(sectionId).classList.add('active');
    element.classList.add('active');
}

var modal = document.getElementById("petModal");

function openModal() {
    if (modal) {
        modal.style.display = "flex";
        document.getElementById("modalTitle").innerText = "Add New Pet";
        document.getElementById("addBtn").style.display = "block";
        document.getElementById("updateBtn").classList.add("hidden");
        document.getElementById("updateBtn").style.display = "none";
        document.querySelector("form").reset();
        document.getElementById("pet-error").style.display = "none";
    }
}

function closeModal() {
    if (modal) modal.style.display = "none";
}

function editPet(pet) {
    if (modal) {
        modal.style.display = "flex";
        document.getElementById("modalTitle").innerText = "Edit Pet";
        document.getElementById("addBtn").style.display = "none";
        document.getElementById("updateBtn").classList.remove("hidden");
        document.getElementById("updateBtn").style.display = "block";
        document.getElementById("pet-error").style.display = "none";

        document.getElementById("pet_id").value = pet.id;
        document.getElementById("p_name").value = pet.name;
        document.getElementById("p_type").value = pet.type;
        document.getElementById("p_age").value = pet.age;
        document.getElementById("p_desc").value = pet.description;
        document.getElementById("p_status").value = pet.status;
    }
}

var confirmModal = document.getElementById("confirmModal");

function confirmAction(url, message, type) {
    if (confirmModal) {
        document.getElementById("confirmText").innerText = message;
        var confirmBtn = document.getElementById("confirmBtnLink");
        confirmBtn.href = url;

        if (type === 'delete' || type === 'reject') {
            confirmBtn.style.background = "#ef4444";
        } else {
            confirmBtn.style.background = "#22c55e";
        }

        confirmModal.style.display = "flex";
    }
}

function closeConfirmModal() {
    if (confirmModal) confirmModal.style.display = "none";
}



function filterRequests(status, btn) {
    const rows = document.querySelectorAll('#adoptions tbody tr');
    const buttons = document.querySelectorAll('.filter-btn');

    buttons.forEach(b => {
        b.classList.remove('active');
    });

    btn.classList.add('active');

    rows.forEach(row => {
        if (status === 'all') {
            row.style.display = '';
        } else {
            if (row.getAttribute('data-status') === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });
}

function validatePetForm() {
    let name = document.getElementById("p_name").value.trim();
    let type = document.getElementById("p_type").value.trim();
    let age = document.getElementById("p_age").value;
    let errorMsg = document.getElementById("pet-error");

    if (name === "" || type === "" || age === "") {
        errorMsg.style.display = "block";
        errorMsg.innerText = "All fields (Name, Type, Age) are required!";
        return false;
    }

    if (age < 0) {
        errorMsg.style.display = "block";
        errorMsg.innerText = "Age cannot be negative!";
        return false;
    }

    errorMsg.style.display = "none";
    return true;
}

function validateAdminForm() {
    let name = document.getElementById("new_admin_name").value.trim();
    let email = document.getElementById("new_admin_email").value.trim();
    let password = document.getElementById("new_admin_password").value.trim();
    let errorMsg = document.getElementById("admin-error");

    if (name === "" || email === "" || password === "") {
        errorMsg.style.display = "block";
        errorMsg.innerText = "All fields are required to add an admin!";
        return false;
    }

    if (!email.includes("@") || !email.includes(".")) {
        errorMsg.style.display = "block";
        errorMsg.innerText = "Please enter a valid email address!";
        return false;
    }

    errorMsg.style.display = "none";
    errorMsg.style.display = "none";
    return true;
}


var viewModal = document.getElementById("viewPetModal");

function viewPetInfo(pet) {
    if (viewModal) {
        viewModal.style.display = "flex";

        let imgPath = pet.image ? "uploads/" + pet.image : "views/assets/images/paw.png";
        document.getElementById("v_image").src = imgPath;

        document.getElementById("v_name").innerText = pet.name;
        document.getElementById("v_owner").innerText = pet.owner_name || "Unknown";
        document.getElementById("v_type").innerText = pet.type;
        document.getElementById("v_age").innerText = pet.age;
        document.getElementById("v_status").innerText = pet.status.charAt(0).toUpperCase() + pet.status.slice(1);
        document.getElementById("v_desc").innerText = pet.description || "No description available.";
    }
}

function closeViewModal() {
    if (viewModal) viewModal.style.display = "none";
}