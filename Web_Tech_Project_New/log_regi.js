function validateRegister() {
    let name = document.getElementById("name").value;
    let email = document.getElementById("email").value;
    let pass = document.getElementById("password").value;
    let confirmPass = document.getElementById("confirm_password").value;
    let errorText = document.getElementById("js-error");

    if (name === "" || email === "" || pass === "" || confirmPass === "") {
        errorText.style.display = "block";
        errorText.innerText = "⚠️ Please fill in all fields!";
        return false;
    }

    if (pass.length < 6) {
        errorText.style.display = "block";
        errorText.innerText = "⚠️ Password must be at least 6 characters!";
        return false;
    }

    if (pass !== confirmPass) {
        errorText.style.display = "block";
        errorText.innerText = "⚠️ Passwords do not match!";
        return false;
    }

    return true;
}

function validateLogin() {
    let email = document.getElementById("login_email").value;
    let pass = document.getElementById("login_pass").value;
    let errorText = document.getElementById("js-login-error");

    if (email === "" || pass === "") {
        errorText.style.display = "block";
        errorText.innerText = "⚠️ Please enter email and password!";
        return false;
    }

    return true;
}

function validateForgot() {
    let email = document.getElementById("forgot_email").value;
    let errorText = document.getElementById("js-forgot-error");

    if (email === "") {
        errorText.style.display = "block";
        errorText.innerText = "⚠️ Please enter your email address!";
        return false;
    }
    return true;
}

function togglePassword(inputId, iconId) {
    let inputField = document.getElementById(inputId);
    let icon = document.getElementById(iconId);

    if (inputField.type === "password") {
        inputField.type = "text"; 
        icon.innerText = "🔓";   
    } else {
        inputField.type = "password"; 
        icon.innerText = "🔒";    
    }
}

function validateForgotEmail() {
    let email = document.getElementById("forgot_email").value;
    let errorText = document.getElementById("js-forgot-error");

    if (email === "") {
        errorText.style.display = "block";
        errorText.innerText = "⚠️ Please enter your email address!";
        return false;
    }
    return true;
}

function validateNewPass() {
    let newPass = document.getElementById("new_pass").value;
    let confirmNewPass = document.getElementById("confirm_new_pass").value;
    let errorText = document.getElementById("js-newpass-error");

    if (newPass === "" || confirmNewPass === "") {
        errorText.style.display = "block";
        errorText.innerText = "⚠️ Please fill in all fields!";
        return false;
    }

    if (newPass.length < 6) {
        errorText.style.display = "block";
        errorText.innerText = "⚠️ Password must be at least 6 characters!";
        return false;
    }

    if (newPass !== confirmNewPass) {
        errorText.style.display = "block";
        errorText.innerText = "⚠️ Passwords do not match!";
        return false;
    }

    return true;
}