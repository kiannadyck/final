/**
 * Jen Shin, Kianna Dyck
 * register.js
 * Javascript for registering a new user.
 * JavaScript validation for register page.
 */

$(document).ready(function () {
    // validates username
    $("#username").blur(validate);
});

// validates user input for password complexity
$('#password').on('keyup', function () {
    if  ($('#password').val() != "") {
        // validate password complexity
        var meetsRequiredComplexity = validatePassword();
        if(!meetsRequiredComplexity) {
            // does not meet password complexity requirements
            $('#message').html('Password must be at least six characters long and contain an uppercase letter, ' +
                'lowercase letter, number, and symbol').css('color', 'red');

        } else {
            $('#message').html("Password passes complexity requirements.").css('color', 'green');
        }

    } else { //empty field
        $('#message').html("");
    }
});

// validates that user input for both password fields match
$('#password2').on('keyup', function () {

    if ($('#password2').val() != "") {
        $('#message').html("");
    }
    if ($('#password').val() != "" && $('#password2').val() != "") {
        $('#message').show();

        if ($('#password').val() == $('#password2').val()) {
            $('#message').html('Passwords match').css('color', 'green');
        } else {
            $('#message').html('Passwords do not match').css('color', 'red');
        }
    } else {
        $('#message').hide();

    }
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

                    } else {
                        // username available and is in a valid format
                        result.html(email + " is valid.").css("color", "green");

                    }
                }
            );


        } else {
            result.html(email + " is not a valid email.").css("color", "red");

        }
    } else {

    }
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
        return true; // this password is good
    }

    return false;

}
