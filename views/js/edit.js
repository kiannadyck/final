/**
 * Jen Shin, Kianna Dyck
 * edit.js
 * Javascript for edit decks page.
 * Has button handlers for updating decks that utilize ajax.
 */

$("#validationMessage").hide();
$("#TextBoxDiv").hide();

addUpdateHandler();
removeHandler();

// click event for 'add' button. This button toggles between presenting the user with question and answer forms for the
// user to fill out, and saving the information entered to the database (if valid).
$("#addNew").click(function() {

    if ($(this).text() == "Add new Flashcard") {
        $(this).text("Save Flashcard to Deck");
        newPair();
    } else {
        savePair();
    }

});

// click event to update the deck name with entered value
$("#updateDeckName").click(function(){
    var deckName = $("#deckName").val();
    var deckId = $("#deckId").val();

    $.post(
        "models/edit.php",
        {
            deckName: deckName,
            deckId: deckId,
            action: "updateDeckName"
        },
        function (result) {
            $("#successFailHeader").text("Update Deck Name");
            //update name in database
            if (result > 0) {
                // Deck name successfully updated in database
                $("#successFailBody").text("Deck name updated successfully!");
            }
            else if (result < 0) {
                // Deck name entered by user fails validation
                $("#successFailBody").text("Deck name cannot be empty.");
            } else {
                // Deck name unsuccessfully updated in database
                $("#successFailBody").text("Update was unsuccessful. Please try again later.");
            }
            $("#successFail").modal("show");
        });

});

// click event to delete the entire deck and all its associated flashcards
$("#deleteDeck").click(function() {
    var deckId = $("#deckId").val();

    //send confirmation if user wants to delete row
    $("#confirmHeader").text("Delete Deck");
    $("#confirmBody").text("Are you sure you want to delete this deck?");
    $("#confirmation").modal("show");

    $("#confirmYes").click(function(){
        $("#confirmation").modal("hide");

        $.post(
            "models/edit.php",
            {
                deckId: deckId,
                action: "deleteDeck"
            },

            function (result) {

                if (result) {
                    //redirect to home if deck successfully deleted from database
                    window.location.replace("328/final/");
                }
                else {
                    // message if failure to remove from database
                    $("#successFailHeader").text("Delete Deck");
                    $("#successFailBody").text("Deletion was unsuccessful. Please try again later.");
                    $("#successFail").modal("show");
                }
            });

    });

});

/**
 * Assigns a click event to each update button in edit flashcards table. When clicked, information for the given row
 * is sent to a processing script using Ajax to handle database changes.
 */
function addUpdateHandler() {
    //add save function to all saveButton elements
    var buttons = document.querySelectorAll("button[name= 'saveButton']");
    var i = 0, length = buttons.length;
    for (i; i < length; i++) {
        if (document.addEventListener) {
            buttons[i].addEventListener("click", function () {

                // get row button is in
                var currentRow = $(this).closest('tr');

                // get the pairId stored in the row
                var pairId = $(currentRow).attr('id');

                // get the question text
                var questionTd = currentRow.find("td:first-child");
                var questionText = questionTd.find(":input").val();

                // get the answer text
                var answerTd = currentRow.find("td:nth-child(2)");
                var answerText = answerTd.find(":input").val();

                $.post(
                    "models/edit.php",
                    {
                        pairId: pairId,
                        question: questionText,
                        answer: answerText,
                        action: "updateRow"
                    },
                    function (result) {
                        $("#successFailHeader").text("Update Flashcard");
                        if (result == -1) {
                            // user input failed validation
                            $("#successFailBody").text("Both question and answer fields cannot be empty.");
                        } else if (result == 0) {
                            // flashcard unsuccessfully updated in database
                            $("#successFailBody").text("Update was unsuccessful. Please try again later.");
                        } else {
                            // flashcard updated successfully in database
                            $("#successFailBody").text("Flashcard updated successfully!");
                        }
                        $("#successFail").modal("show");
                    });

            });
        }
    };
}

/**
 * Assigns a click event to each remove button in edit flashcards table. When clicked, information for the given row
 * is sent to a processing script using Ajax to handle database changes.
 */
function removeHandler() {
    var removeButtons = document.querySelectorAll("button[name= 'remove']");
    var y = 0, ylength = removeButtons.length;
    for (y; y < ylength; y++) {
        if (document.addEventListener) {
            removeButtons[y].addEventListener("click", function () {
                // get row button is in
                var currentRow = $(this).closest('tr');

                // get the pairId stored in the row
                var pairId = $(currentRow).attr('id');

                //send confirmation if user wants to delete row
                $("#confirmHeader").text("Remove Flashcard");
                $("#confirmBody").text("Are you sure you want to remove this flashcard?");
                $("#confirmation").modal("show");

                $("#confirmYes").click(function(){
                    $("#confirmation").modal("hide");

                    /*Send delete request to database*/
                    $.post(
                        "models/edit.php",
                        {
                            pairId: pairId,
                            action: "deleteRow"
                        },
                        function (result) {
                            //remove row from browser if true (flashcard successfully deleted from database)
                            if (result) {
                                currentRow.remove();
                            }
                            else {
                                // flashcard unsuccessfully removed from database
                                $("#successFailHeader").text("Remove Flashcard");
                                $("#successFailBody").text("Removal was unsuccessful. Please try again later.");
                                $("#successFail").modal("show");
                            }
                        });
                });

            });
        }
    }
}

/**
 * This function displays a question and answer form.
 */
function newPair()
{
    $("#TextBoxDiv").show();

}

/**
 * This function retrieves the values entered in the question and answer forms and sends it for processing using ajax.
 * If valid and successfully added to database, a new row is appended to the table with entered information.
 */
function savePair()
{
    var question = $("#question").val();
    var answer = $("#answer").val();
    var deckId = $("#deckId").val();

    $.post(
        "models/edit.php",
        {
            question: question,
            answer: answer,
            deckId: deckId,
            action: "addRow"
        },
        function (result) {

            //add row from browser if true
            if (result > 0) {
                //append to table
                var text = "<tr id='" + result + "'>";
                var tdQ = "<td><textarea class='form-control'  rows='5' style='resize:none'>" + question + "</textarea></td>";
                var tdA = "<td><textarea class='form-control'  rows='5' style='resize:none'>" + answer + "</textarea></td>";
                var buttons = "<td><button class='btn btn-outline-warning' id ='saveButton' name ='saveButton'>Update</button><br/><br>" +
                    "<button class='btn btn-outline-danger' id = 'remove' name ='remove'>Remove</button></td></tr>";

                var table = $("table");
                table.append(text + tdQ + tdA + buttons);

                /*Assign click events to buttons*/
                removeHandler();
                addUpdateHandler();

                /*Clear question and answer fields in preparation for next entered question*/
                $("#question").val("");
                $("#answer").val("");

                // Hide validation message if present
                $("#validationMessage").hide();

                /*Hide Question and Answer Fields until button clicked again*/
                $("#TextBoxDiv").hide();

                $("#addNew").text("Add new Flashcard");
            }
            else if (result < 0) {
                // Failed validation
                $("#validationMessage").show();
                $("#addNew").text("Save Flashcard to Deck");

            }
            else {
                // Failed to add new flashcard to database
                $("#successFailHeader").text("Add Flashcard");
                $("#successFailBody").text("Addition of new flashcard was unsuccessful. Please try again later.");
                $("#successFail").modal("show");
            }
        });
}