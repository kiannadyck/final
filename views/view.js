/**
 * Jen Shin, Kianna Dyck
 * view.js
 * Javascript for viewing a deck.
 * Allows user to see an answer to a question, navigate within deck.
 */
// initial hide
$(".answer").hide();
// initial show answer
showAnswer();

var slideIndex = 1;
showDivs(slideIndex);

function plusDivs(n) {

    showDivs(slideIndex += n);
    $(".answer").hide(); // hide after transition to next card
    $(".showAnswer").show(); // show button after clicking next or previous
}

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

function showAnswer()
{
    $(".showAnswer").click(function() {
        $(".answer").show();
        $(".showAnswer").hide();

    });
}



$("#next").click(function() {
    plusDivs(1);
});


$("#previous").click(function() {
    plusDivs(-1);
});

