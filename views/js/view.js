/**
 * Jen Shin, Kianna Dyck
 * view.js
 * Javascript for viewing a deck.
 * Allows user to see an answer to a question, navigate within deck.
 */
// initial hide of answer
$(".answer").hide();
// initial show answer
showAnswer();

var slideIndex = 1;
showDivs(slideIndex);

// click event when user clicks next button
$("#next").click(function() {
    plusDivs(1);
});

// click event when user clicks previous button
$("#previous").click(function() {
    plusDivs(-1);
});

/**
 * This function passes value to showDivs that indicates if flashcard shown is next or previous.
 * @param n Value passed from next/previous click events
 */
function plusDivs(n) {

    showDivs(slideIndex += n);
    $(".answer").hide(); // hide after transition to next card
    $(".showAnswer").show(); // show button after clicking next or previous
}

/**
 * This function displays the next/previous flashcard.
 * @param n Value indicating previous or next flashcard.
 */
function showDivs(n) {

    var i;
    var x = document.getElementsByClassName("card");
    if (n == x.length) {
        $("#next").hide()
    } else {
        $("#next").show()

    }
    if (n == 1) {
        $("#previous").hide();
    } else {
        $("#previous").show();

    }
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";

    }
    x[slideIndex-1].style.display = "block";

}

/**
 * Click event to show answers and hide the show answer button
 */
function showAnswer()
{
    $(".showAnswer").click(function() {
        $(".answer").show();
        $(".showAnswer").hide();

    });
}

