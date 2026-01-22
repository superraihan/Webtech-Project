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

function validateProfile() {
    let name = document.getElementById("name").value;
    let phone = document.getElementById("phone").value;
    let address = document.getElementById("address").value;
    let password = document.getElementById("password").value;
    let errorMsg = "";
    let errorElement = document.getElementById("js-error");

    if (name.trim() == "" || phone.trim() == "" || address.trim() == "" || password.trim() == "") {
        errorMsg = "All fields are required!";
    } else if (phone.length < 11) {
        errorMsg = "Phone number must be at least 11 digits!";
    }

    if (errorMsg != "") {
        errorElement.style.display = "block";
        errorElement.innerText = errorMsg;
        return false;
    }

    errorElement.style.display = "none";
    return true;
}