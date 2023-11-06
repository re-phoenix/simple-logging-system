

// let searchElement = document.querySelectorAll('.Search'); //REM: ob my gulay...

// searchElement.forEach(function(element) {
//     element.style.display = 'none';
// });

//REM: Select the elements with the class "log_in.php"
let loginElements = document.querySelectorAll('.LogIn'); //REM: ob my gulay...

//REM: Loop through the selected elements and hide them
loginElements.forEach(function(element) {
    element.style.display = 'inline';
});

let registrationElement = document.querySelectorAll('.Registration'); //REM: ob my gulay...

registrationElement.forEach(function(element) {
    element.style.display = 'inline';
});

document.addEventListener("DOMContentLoaded", function() {
    const signInForm = document.querySelector("#SIGN_IN_FORM");
    const signUpForm = document.querySelector("#SIGN_UP_FORM");

    if (signInForm) {
        signInForm.addEventListener("submit", function(event) {
            event.preventDefault();

            const elementIdUserName = document.querySelector(".TxtLogInUserName");
            const elementIdUserPassword = document.querySelector(".TxtLogInPassword");
            const userName = elementIdUserName.value;
            const userPassword = elementIdUserPassword.value;

            console.log("::: DEBUG: ");
            console.log("userId: ", userName);
            console.log("userPass: ", userPassword);

            fetch("./database/authenticate.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ userId: userName, userPass: userPassword }),
            })
            .then(response => response.json())
            .then(data => {
                console.log("Received data:", data);
                elementIdUserPassword.value = "";
                if (data.isSuccess) {
                    alert(data.message);
                    window.location.href = "../../index.html";
                    if (data.isAdmin) {
                        //REM: TODO-HERE
                    }
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error("::: Error:", error.message);
            });
        });
    }

    if (signUpForm) {
        signUpForm.addEventListener("submit", function(event) {
            event.preventDefault();

            const elementSignUpUserName = document.querySelector(".TxtSignUpUserName");
            const elementSignUpPassword= document.querySelector(".TxtSignUpPassword");
            const elementSignUpRePassword = document.querySelector(".TxtSignUpRePassword");
            const elementSignUpEmail = document.querySelector(".TxtSignUpEmail");

            const userName = elementSignUpUserName.value;
            const userPassword = elementSignUpPassword.value;
            const userRePassword = elementSignUpRePassword.value;
            const userEmail = elementSignUpEmail.value;

            console.log("::: DEBUG: ");
            console.log("userId: ", userName);
            console.log("userPass: ", userPassword);
            console.log("userRePass: ", userRePassword);
            console.log("userEmail: ", userEmail);

            fetch("./database/register.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ 
                    userId: userName, 
                    userPass: userPassword, userRePass: userRePassword,
                    userEmail: userEmail
                }),
            })
            .then(response => response.json())
            .then(data => {
                console.log("Received data:", data);
                userPassword.value = "";
                userRePassword.value = "";
                if (data.isSuccess) {
                    window.location.href = "../../index.html";
                    if (data.isAdmin) {
                        //REM: TODO-HERE
                    }
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error("::: Error:", error.message);
            });
        });
    }
});


// REM: Not good way of doing things, but it is working,
// REM: This will be replace in the future with a proper way of doing things.
// REM: Checkbox for either showing the password or otherwise ...
const showPasswordCheckbox = document.querySelector(".ChkboxShowPassword");

if (showPasswordCheckbox) {
    showPasswordCheckbox.addEventListener("change", function() {
        const passwordInputs = document.querySelectorAll(".TxtPassword");
        passwordInputs.forEach(input => {
            if (input) {
                input.type = this.checked ? "text" : "password";
            }
        });
    });
}




