function showSection(sectionId) {
    const sections = document.querySelectorAll('.section-content');
    sections.forEach(section => {
        section.classList.remove('active');
    });

    const buttons = document.querySelectorAll('.nav-btn');
    buttons.forEach(btn => {
        btn.classList.remove('active');
    });

    document.getElementById(sectionId).classList.add('active');
    event.currentTarget.classList.add('active');
}

var modal = document.getElementById("petModal");

function openModal() {
    if(modal) {
        modal.style.display = "flex";
        document.getElementById("modalTitle").innerText = "Add New Pet";
        document.getElementById("addBtn").style.display = "block";
        document.getElementById("updateBtn").style.display = "none";
        document.querySelector("form").reset();
    }
}

function closeModal() {
    if(modal) modal.style.display = "none";
}

function editPet(pet) {
    if(modal) {
        modal.style.display = "flex";
        document.getElementById("modalTitle").innerText = "Edit Pet";
        document.getElementById("addBtn").style.display = "none";
        document.getElementById("updateBtn").style.display = "block";

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
    if(confirmModal) {
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
    if(confirmModal) confirmModal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
    if (event.target == confirmModal) {
        confirmModal.style.display = "none";
    }
}

function filterRequests(status) {
    const rows = document.querySelectorAll('#adoptions tbody tr');
    const buttons = document.querySelectorAll('.filter-btn');
    
    buttons.forEach(btn => btn.classList.remove('active'));
    event.currentTarget.classList.add('active');
    
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