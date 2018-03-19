/**
 * Jen Shin, Kianna Dyck
 * register.js
 * Javascript for registering a new user.
 * Does not allow submit button until all fields valid.
 */
document.getElementById('submit').disabled = true;
var count = 0;
var emailOkay = 0;

$('#password').on('keyup', function () {
    if  ($('#password').val() != "") {
        // validate password complexity
        var meetsRequiredComplexity = validatePassword();
        if(!meetsRequiredComplexity) {
            // does not meet password complexity requirements
            $('#message').html('Password must be at least six characters long and contain an uppercase letter, ' +
                'lowercase letter, number, and symbol').css('color', 'red');
            count = 0;
            submitOkay();
        } else {
            $('#message').html("Password passes complexity requirements.").css('color', 'green');
        }

    } else { //empty field
        $('#message').html("");
    }
});


$('#password2').on('keyup', function () {

    if ($('#password2').val() != "") {
        $('#message').html("");

    }
    if ($('#password').val() != "" && $('#password2').val() != "") {
        $('#message').show();


        if ($('#password').val() == $('#password2').val()) {
            $('#message').html('Passwords match').css('color', 'green');
            //user allowed to click submit button
            // document.getElementById('submit').disabled = false;
            count = 1;
            submitOkay();
        } else {
            $('#message').html('Passwords do not match').css('color', 'red');
            count = 0;
            submitOkay();
        }
    } else {
        //if either password field is empty, disable submit
        $('#message').hide();
        count = 0;
        submitOkay();
    }
});

$(document).ready(function () {
    //keyup
    $("#username").blur(validate);
});

function validateEmail(email) {
    var regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    var n = email.search(regex);
    return n >= 0;
}

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
                }
            );

            submitOkay();
        } else {
            result.html(email + " is not a valid email.").css("color", "red");
            emailOkay = 0;
            submitOkay();
        }
    } else {
        emailOkay = 0;

        submitOkay();
    }
}
function submitOkay() {
    if (emailOkay > 0 && count > 0) {

        document.getElementById('submit').disabled = false;

    } else {
        document.getElementById('submit').disabled = true;

    }
}

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
