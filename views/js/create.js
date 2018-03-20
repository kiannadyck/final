/**
 * Jen Shin, Kianna Dyck
 * edit.js
 * Javascript for the edit page.
 * Has button handlers to remove and add additional question and answer forms for user to fill out.
 */

// If user is logging in for the first time, message appears
var cookies = document.cookie;

// get cookie pairs (this is an array if multiple cookies)
var cookie = cookies.split(';');

var cookieValue = cookie[0].split('=')[1];

if (cookieValue = "new") {
    $("#newUser").modal('show');
    document.cookie = "user=returning";
}


// handlers to add and remove question and answer forms
var counter = 2;

// event handler
$("#addButton").click(function () {

    // dynamically create a div with attribute for id set to TextBoxDiv + a number
    var newTextBoxDiv = $(document.createElement('div'))
        .attr("id", 'TextBoxDiv' + counter);

    // create label after newly created div
    newTextBoxDiv.after().html('<label>Question #'+ counter + ' : </label>' +
        ' <textarea class="form-control" rows="5" name="question[]" ' +
        'id="question' + counter + '" ></textarea>' + '<br>' + '<label>Answer #'+ counter + ' : </label>' +
        ' <textarea class="form-control" rows="5" name="answer[] ' +
        'id="answer' + counter + '" ></textarea>' + '<br/>' + '<hr>');

    // append new div to TextBoxesGroup
    newTextBoxDiv.appendTo("#TextBoxesGroup");

    // increment counter for unique id names
    counter++;
});

$("#removeButton").click(function () {
    if(counter == 1){
        alert("No more flashcards to remove");
        return false;
    }

    // decrement counter
    counter--;

    // remove
    $("#TextBoxDiv" + counter).remove();
    return true;
});