<?php
/**
 * Created by PhpStorm.
 * User: kyongah
 * Date: 2/23/18
 * Time: 12:26 PM
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
     * @return string associative array of questions and answers
     */
    public function getQuestionAnswer()
    {
        return $this->_questionAnswer;
    }

    /**
     * @param array $questionAnswer associative array of strings
     */
    public function setQuestionAnswer($questionAnswer)
    {
        $this->_questionAnswer = $questionAnswer;
    }



}