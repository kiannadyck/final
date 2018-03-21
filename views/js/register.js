/**
 * Jen Shin, Kianna Dyck
 * register.js
 * Javascript for registering a new user.
 * Does not allow submit button until all fields valid.
 */
document.getElementById('submit').disabled = true;
var count = 0;
var emailOkay = 0;
var complexity = 0;

$(document).ready(function () {
    // validates username
    $("#username").blur(validate);
});

submitOkay();
// validates user input for password complexity
$('#password').on('keyup', function () {
    if ($('#password').val() !== "") {
        // validate password complexity
        var meetsRequiredComplexity = validatePassword();
        if (!meetsRequiredComplexity) {
            // does not meet password complexity requirements
            $('#pmessage').html(' * Password must be at least six characters long and contain an uppercase letter, ' +
                'lowercase letter, number, and symbol').css('color', 'red');
            count = 0;
            submitOkay();
        } else {
            $('#pmessage').html(" * Password passes complexity requirements.").css('color', 'green');
            $('#message').html("");

        }

    } else { //empty field
        $('#pmessage').html("");
        $('#message').html("");

    }
    submitOkay();
});

$('#password2').on('keyup', function () {

// validates that user input for both password fields match
    if ($('#password').val() !== "" && $('#password2').val() !== "") {
        //   $('#message').show();


        if ($('#password').val() === $('#password2').val()) {
            $('#message').html('Passwords match').css('color', 'green');
            //user allowed to click submit button
            count = 1;
            //submitOkay();
        } else {
            $('#message').html('Passwords do not match').css('color', 'red');
            count = 0;
            //submitOkay();
        }

        submitOkay();
    } else if ($('#password').val() === "" || $('#password2').val() === "") {
        $('#message').html("");

    } else {
        //if either password field is empty, disable submit
        $('#message').html("");
        $('#pmessage').html("");
    }
    submitOkay();
});


/**
 * Validates user input for username email is in correct format.
 * @param email
 * @returns {boolean}
 */
function validateEmail(email) {
    var regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    var n = email.search(regex);
    return n >= 0;
}

/**
 * Verifies entered username email is available/not already in use
 */
function validate() {
    var result = $("#result");
    var email = $("#username").val();
    result.html("");

    if(email != "") {
        if (validateEmail(email)) {
            // check if username is already in use
            $.post(
                "models/checkUsername.php",
                {email : email},
                function(output) {
                    if(output != "success") {
                        // username already in use
                        result.html(output).css('color', 'red');
                        emailOkay = 0;
                    } else {
                        // username available and is in a valid format
                        result.html(email + " is valid.").css("color", "green");
                        emailOkay = 1;

                    }
                    submitOkay();

                }

            );

        } else {
            result.html(email + " is not a valid email.").css("color", "red");
            emailOkay = 0;
            submitOkay();
        }
    } else {
        emailOkay = 0;
        submitOkay();
    }
    submitOkay();
}

/**
 * This function validates if password entered meets complexity requirements using regex.
 * @returns {boolean}
 */
function validatePassword()
{
    var password = $('#password').val();

    // check if at least one digit is in password
    var digit = '\\d+';
    // check if at least one lowercase
    var lowercase = '[a-z]+';
    // check for uppercase
    var uppercase = '[A-Z]+';
    //check for symbol
    var symbol = '[^\\w]';

    var digitRegex = new RegExp(digit);
    var d = password.search(digitRegex);

    var lowerRegex = new RegExp(lowercase);
    var l = password.search(lowerRegex);

    var upperRegex = new RegExp(uppercase);
    var u = password.search(upperRegex);

    var symbolRegex = new RegExp(symbol);
    var s = password.search(symbolRegex);

    if (d >= 0 && l >= 0 && u >= 0 && s >= 0 && password.length >= 6) {
        complexity = 1;
        submitOkay();
        return true; // this password is good
    }
    complexity = 0;
    submitOkay();
    return false;

}

/**
 * If email username is unique, complexity of password and
 * both password fields match,
 * then enable the submit button.
 * Otherwise, button stays disabled.
 * Checked at every case (any changes to fields)
 * and also when page first loads.
 */
function submitOkay() {
    if (complexity > 0 && emailOkay > 0 && count > 0) {

        document.getElementById('submit').disabled = false;

    } else {
        document.getElementById('submit').disabled = true;

    }
}