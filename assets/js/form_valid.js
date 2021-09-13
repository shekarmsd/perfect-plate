function validateForm() {

    var username = document.getElementById('name');
    var email = document.getElementById('email');
    var password = document.getElementById('password');
    var confirm_password = document.getElementById('confirm_password');


    if (nameVal(username)) {
        if (emailVal(email)) {
            if (phoneVal(phone)) {
                if (passVal(password, confirm_password)) {
                    return true;
                }
            }
        }
    }

    return false;
}

function nameVal(username) {
    var letters = /^[A-Za-z]+$/;
    if (username.value == "") {
        alert('Please enter your name.');
        username.focus();
        return false;
    }
    if (username.value.match(letters)) {
        return true;
    } else {
        alert('Username must have alphabet characters only');
        username.focus();
        return false;
    }
}

function emailVal(email) {
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if (email.value == "") {
        alert("Please enter your email address.");
        email.focus();
        return false;
    }
    if (email.value.match(mailformat)) {
        return true;
    } else {
        alert("You have entered an invalid email address!");
        email.focus();
        return false;
    }
}

function phoneVal(phone) {

    if (phone.value == "") {
        alert("Please enter your telephone number.");
        phone.focus();
        return false;
    }
    if (/^\d{10}$/.test(phone.value)) {
        return true;
    } else {
        alert("Invalid number; must be ten digits");
        phone.focus();
        return false;
    }

}

function passVal(password, confirm_password) {
    if ((password.value == "") && (confirm_password.value == "")) {
        alert("Please enter your password.");
        password.focus();
        return false;
    }

    if (password.value == confirm_password.value) {
        return true;
    } else {
        alert("Password did not match!");
        confirm_password.focus();
        return false;
    }

}