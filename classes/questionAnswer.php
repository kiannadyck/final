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

    private $_question;
    private $_answer;

    /**
     * QuestionAnswer constructor.
     * @param $name string
     * @param $id int deckId
     * @param $question array of questions
     * @param $answer array of answers
     */
    function __construct($name, $id, $question, $answer)
    {
        parent::__construct($name, $id);
        $this->_question = $question;
        $this->_answer = $answer;
    }

    /**
     * Gets array of questions and answers.
     * @return string array of questions and answers
     */
    public function getQuestions()
    {
        return $this->_question;
    }

    public function getAnswers()
    {
        return $this->_answer;
    }

    /**
     * Sets  array of questions and answers.
     * @param array $questionAnswer associative array of strings
     */
    public function setQuestionAnswer($deckId, $question, $answer)
    {
        $this->_question[$deckId] = $question;
        $this->_answer[$deckId] = $answer;

    }



}