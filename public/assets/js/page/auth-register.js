"use strict";

$(".pwstrength").pwstrength();

// Show Password Function
const passwordField = document.getElementById("password");
const confirmPasswordField = document.getElementById("password-confirm");
const showPasswordCheckbox = document.getElementById("show-password");

showPasswordCheckbox.addEventListener("change", function () {
    if (showPasswordCheckbox.checked) {
        passwordField.type = "text";
        confirmPasswordField.type = "text";
    } else {
        passwordField.type = "password";
        confirmPasswordField.type = "password";
    }
});
