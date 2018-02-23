<?php
/**
 * Date: 2/23/18
 * Time: 12:26 PM
 * questionAnswer.php
 *
 * This class instantiates a questionAnswer object
 * that contains key value pairs for quizzing.
 * Extends from Deck object.
 *
 * @author Kianna <kdyck@mail.greenriver.edu> and Jen <jshin13@mail.greenriver.edu>
 * @copyright 2018
 */

class QuestionAnswer extends Deck
{

    private $_questionAnswer;

    /**
     * QuestionAnswer constructor.
     * @param $name string
     * @param $array associative array of question-answer pairs
     */
    function __construct($name, $array)
    {
        parent::__construct($name);
        $this->_notes = $array;
    }

    /**
     * Gets associative array of questions and answers.
     * @return string associative array of questions and answers
     */
    public function getQuestionAnswer()
    {
        return $this->_questionAnswer;
    }

    /**
     * Sets associative array of questions and answers.
     * @param array $questionAnswer associative array of strings
     */
    public function setQuestionAnswer($questionAnswer)
    {
        $this->_questionAnswer = $questionAnswer;
    }



}